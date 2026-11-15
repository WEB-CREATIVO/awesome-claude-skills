# Asistente de WhatsApp para clínica dental

Atiende los WhatsApp de la clínica, responde dudas frecuentes y reserva / consulta / cancela citas en Google Calendar.

```
Paciente ──WhatsApp──► Meta Cloud API ──webhook──► FastAPI (/webhook)
                                                       │
                                        Claude (tool use) ◄──► Google Calendar
                                                       │            (huecos, citas)
                                               SQLite (historial, dedupe, derivaciones)
```

**Qué hace:** FAQs, horarios, precios orientativos, ubicación · propone huecos reales según la agenda · reserva con confirmación · lista y cancela *solo* las citas del propio número · deriva a recepción (y avisa al personal) en urgencias, quejas o dudas clínicas.

## Estado: qué está probado y qué no

- ✅ Probado con tests automáticos (14): cálculo de huecos, doble reserva, validación de horas, que un paciente no pueda cancelar citas ajenas, bucle del agente con un Claude simulado, firma del webhook, deduplicado de reintentos y desvío de urgencias.
- ⚠️ **No probado contra los servicios reales** (WhatsApp Cloud API, Google Calendar, API de Claude): no había credenciales. Los tests usan una agenda y un modelo simulados. Haz las pruebas de abajo antes de poner pacientes reales.

## Puesta en marcha

1. **Datos de la clínica:** edita `clinic.yaml` (ahora hay datos de ejemplo: horario, tratamientos, duraciones, precios, FAQs). El bot solo afirma lo que esté ahí.
2. **Probar el cerebro sin WhatsApp ni Google** (agenda en memoria):
   ```bash
   pip install -r requirements-dev.txt
   pytest
   ANTHROPIC_API_KEY=sk-... python -m app.cli
   ```
3. **Google Calendar:** crea una cuenta de servicio en Google Cloud, activa *Google Calendar API*, y **comparte el calendario de la clínica con el email de esa cuenta** con permiso «Hacer cambios en eventos». Rellena `GOOGLE_CALENDAR_ID` y `GOOGLE_SERVICE_ACCOUNT_JSON`.
4. **WhatsApp Cloud API (Meta):** app en Meta for Developers con el producto WhatsApp, un número de teléfono, un token **permanente** de usuario del sistema, y el *App Secret*. Configura el webhook en `https://TU-DOMINIO/webhook` con tu `WHATSAPP_VERIFY_TOKEN` y suscríbete al campo `messages`.
5. **Variables:** copia `.env.example` a `.env` y rellénalo.
6. **Arranque** (necesita HTTPS público; Meta no llama a localhost):
   ```bash
   uvicorn app.main:app --host 0.0.0.0 --port 8000
   # o: docker build -t dental-bot . && docker run --env-file .env -v bot-data:/data -p 8000:8000 dental-bot
   ```

### Pruebas de aceptación recomendadas (con tu número)
- «¿Cuánto cuesta una limpieza?» → responde con el dato de `clinic.yaml`.
- Pregunta algo que **no** esté en `clinic.yaml` → debe decir que no lo sabe y ofrecer recepción, no inventar.
- Reserva completa → comprueba que aparece el evento en Calendar con el teléfono y tratamiento.
- Intenta reservar un hueco ya ocupado desde el propio Calendar → debe ofrecer alternativas.
- «Me sangra la encía y no para» → respuesta inmediata con teléfono de urgencias y aviso a recepción.
- Cancelar y cambiar cita.

## Decisiones de diseño

- **El teléfono lo pone el servidor**, no el modelo: Claude no puede reservar/cancelar a nombre de otro número.
- **La disponibilidad se revalida en servidor** al reservar; el modelo no puede forzar una hora fuera de agenda.
- **Urgencias con doble red:** instrucciones al modelo *y* una detección determinista por palabras clave (`emergency_keywords`) que deriva sin pasar por el modelo. Es conservadora: habrá falsos positivos.
- Meta **firma** cada webhook (HMAC-SHA256); se rechaza lo que no lleve firma válida. Los reintentos de Meta se deduplican por id de mensaje.
- Al derivar a recepción, el bot **calla 12 h** en esa conversación.

## Limitaciones conocidas (léelas)

- **Un solo proceso:** los bloqueos anti-doble-reserva y SQLite son locales. Para varias réplicas hace falta una base de datos/bloqueo compartido. Si alguien reserva a mano en Calendar en el mismo segundo que el bot, existe una ventana mínima de carrera.
- **Una sola agenda** (un calendario para toda la clínica). Para varios dentistas/gabinetes hay que ampliar el modelo (un calendario por profesional y elegir).
- **Ventana de 24 h de WhatsApp:** solo se puede responder con texto libre dentro de las 24 h posteriores al último mensaje del paciente. Los **avisos al personal** (`STAFF_WHATSAPP`) por texto libre pueden **fallar si ese número no ha escrito al bot en 24 h**; para fiabilidad usa una plantilla aprobada o cambia `notify_staff` por email/Slack. Del mismo modo, **recordatorios de cita** (no incluidos) requieren plantillas aprobadas por Meta.
- **Cómo responde el personal humano** tras una derivación depende de tu configuración de WhatsApp Business (app vs. API); debes decidirlo — el bot solo avisa y se calla.
- Solo mensajes de **texto**; audios e imágenes reciben una respuesta pidiendo texto/llamada.
- El historial persistido es solo texto (no los resultados internos de herramientas); el bot vuelve a consultar la agenda cuando hace falta.

## Privacidad (datos de salud)

Los mensajes de pacientes son datos personales y pueden revelar datos de salud (categoría especial en el RGPD). Antes de salir a producción revisa con tu asesor/DPO: base legal y aviso de privacidad al paciente (p. ej. en el primer mensaje), contratos de encargado de tratamiento con Meta, Google y Anthropic, ubicación/retención de `bot.db` y política de borrado. Esto no es asesoramiento legal.
