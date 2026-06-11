'use client'
import { useState, useMemo } from 'react'
import { useRouter } from 'next/navigation'
import { createClient } from '@/lib/supabase/client'
import { CheckCircle, ChevronRight, ChevronLeft, Calendar, Clock, Scissors, User, Star } from 'lucide-react'
import type { Service, Barber, Profile } from '@/lib/types'
import { formatPrice, formatTime, generateTimeSlots, getAvatarUrl, DAY_NAMES } from '@/lib/utils'
import Image from 'next/image'
import { addDays, format, startOfToday } from 'date-fns'
import { es } from 'date-fns/locale'

const STEPS = ['Servicio', 'Barbero', 'Fecha y hora', 'Confirmar']

function StepIndicator({ step }: { step: number }) {
  return (
    <div className="flex items-center justify-center mb-10 gap-0">
      {STEPS.map((label, i) => (
        <div key={i} className="flex items-center">
          <div className="flex flex-col items-center">
            <div
              className={`w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-all ${
                i < step
                  ? 'gold-gradient text-white'
                  : i === step
                  ? 'border-2 border-gold-500 text-gold-400'
                  : 'border-2 border-gray-700 text-gray-600'
              }`}
            >
              {i < step ? <CheckCircle className="w-4 h-4" /> : i + 1}
            </div>
            <span className={`text-xs mt-1 font-medium ${i === step ? 'text-gold-400' : 'text-gray-600'}`}>
              {label}
            </span>
          </div>
          {i < STEPS.length - 1 && (
            <div className={`w-12 sm:w-20 h-0.5 mb-4 mx-1 ${i < step ? 'bg-gold-600' : 'bg-gray-800'}`} />
          )}
        </div>
      ))}
    </div>
  )
}

export default function BookingWizard({
  services,
  barbers,
  barberServices,
  workingHours,
  userId,
}: {
  services: Service[]
  barbers: (Barber & { profile: Profile })[]
  barberServices: { barber_id: string; service_id: string }[]
  workingHours: { barber_id: string; day_of_week: number; start_time: string; end_time: string; is_active: boolean }[]
  userId?: string
}) {
  const router = useRouter()
  const [step, setStep] = useState(0)
  const [selectedService, setSelectedService] = useState<Service | null>(null)
  const [selectedBarber, setSelectedBarber] = useState<(Barber & { profile: Profile }) | null>(null)
  const [selectedDate, setSelectedDate] = useState<string>('')
  const [selectedTime, setSelectedTime] = useState<string>('')
  const [notes, setNotes] = useState('')
  const [loading, setLoading] = useState(false)
  const [success, setSuccess] = useState(false)
  const [needsAuth, setNeedsAuth] = useState(false)

  // Available barbers for selected service
  const eligibleBarbers = useMemo(() => {
    if (!selectedService) return barbers
    const bids = barberServices.filter((bs) => bs.service_id === selectedService.id).map((bs) => bs.barber_id)
    return bids.length > 0 ? barbers.filter((b) => bids.includes(b.id)) : barbers
  }, [selectedService, barbers, barberServices])

  // Next 30 days calendar
  const today = startOfToday()
  const dateOptions = Array.from({ length: 30 }, (_, i) => addDays(today, i + 1))

  const availableDates = useMemo(() => {
    if (!selectedBarber) return dateOptions
    const activeDays = workingHours
      .filter((wh) => wh.barber_id === selectedBarber.id && wh.is_active)
      .map((wh) => wh.day_of_week)
    return dateOptions.filter((d) => activeDays.includes(d.getDay()))
  }, [selectedBarber, workingHours, dateOptions])

  const timeSlots = useMemo(() => {
    if (!selectedBarber || !selectedDate || !selectedService) return []
    const dayOfWeek = new Date(selectedDate + 'T00:00:00').getDay()
    const wh = workingHours.find(
      (w) => w.barber_id === selectedBarber.id && w.day_of_week === dayOfWeek && w.is_active
    )
    if (!wh) return []
    return generateTimeSlots(wh.start_time, wh.end_time, selectedService.duration_minutes)
  }, [selectedBarber, selectedDate, selectedService, workingHours])

  const handleBook = async () => {
    if (!selectedService || !selectedBarber || !selectedDate || !selectedTime) return

    if (!userId) {
      setNeedsAuth(true)
      return
    }

    setLoading(true)
    const supabase = createClient()

    const [h, m] = selectedTime.split(':').map(Number)
    const endMinutes = h * 60 + m + selectedService.duration_minutes
    const endH = Math.floor(endMinutes / 60)
    const endM = endMinutes % 60
    const endTime = `${String(endH).padStart(2, '0')}:${String(endM).padStart(2, '0')}`

    const { error } = await supabase.from('appointments').insert({
      client_id: userId,
      barber_id: selectedBarber.id,
      service_id: selectedService.id,
      appointment_date: selectedDate,
      start_time: selectedTime,
      end_time: endTime,
      total_price: selectedService.price,
      notes: notes || null,
      status: 'pending',
    })

    setLoading(false)

    if (!error) {
      setSuccess(true)
    }
  }

  if (success) {
    return (
      <div className="card p-12 text-center animate-slide-up">
        <div className="w-20 h-20 gold-gradient rounded-full flex items-center justify-center mx-auto mb-6">
          <CheckCircle className="w-10 h-10 text-white" />
        </div>
        <h2 className="text-3xl font-black mb-3">¡Cita reservada!</h2>
        <p className="text-gray-400 mb-2">
          Tu cita para <span className="text-white font-semibold">{selectedService?.name}</span> con{' '}
          <span className="text-white font-semibold">{selectedBarber?.profile?.full_name}</span> ha sido registrada.
        </p>
        <p className="text-gold-400 font-semibold mb-8">
          {selectedDate && new Date(selectedDate + 'T00:00:00').toLocaleDateString('es-MX', {
            weekday: 'long', day: 'numeric', month: 'long'
          })} a las {formatTime(selectedTime)}
        </p>
        <div className="flex gap-3 justify-center">
          <button
            onClick={() => { setStep(0); setSelectedService(null); setSelectedBarber(null); setSelectedDate(''); setSelectedTime(''); setSuccess(false) }}
            className="btn-secondary"
          >
            Reservar otra
          </button>
          <button onClick={() => router.push('/dashboard')} className="btn-primary">
            Ver mis citas →
          </button>
        </div>
      </div>
    )
  }

  if (needsAuth) {
    return (
      <div className="card p-12 text-center animate-slide-up">
        <User className="w-16 h-16 text-gold-400 mx-auto mb-4" />
        <h2 className="text-2xl font-black mb-3">Inicia sesión para confirmar</h2>
        <p className="text-gray-400 mb-8">Necesitas una cuenta para completar tu reserva</p>
        <div className="flex gap-3 justify-center">
          <button onClick={() => router.push('/login')} className="btn-primary">
            Iniciar sesión
          </button>
          <button onClick={() => router.push('/register')} className="btn-secondary">
            Crear cuenta
          </button>
        </div>
      </div>
    )
  }

  const CATEGORIES = [...new Set(services.map((s) => s.category))]

  return (
    <div className="card overflow-hidden">
      <div className="p-8">
        <StepIndicator step={step} />

        {/* Step 0: Service */}
        {step === 0 && (
          <div className="animate-slide-up space-y-6">
            <h2 className="text-xl font-bold">¿Qué servicio deseas?</h2>
            {CATEGORIES.map((cat) => (
              <div key={cat}>
                <h3 className="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">{cat}</h3>
                <div className="space-y-2">
                  {services.filter((s) => s.category === cat).map((s) => (
                    <button
                      key={s.id}
                      onClick={() => { setSelectedService(s); setSelectedBarber(null); setSelectedDate(''); setSelectedTime('') }}
                      className={`w-full flex items-center gap-4 p-4 rounded-xl border text-left transition-all ${
                        selectedService?.id === s.id
                          ? 'border-gold-500 bg-gold-500/10'
                          : 'border-gray-800 hover:border-gray-700 bg-gray-950'
                      }`}
                    >
                      <div className={`w-5 h-5 rounded-full border-2 shrink-0 flex items-center justify-center ${
                        selectedService?.id === s.id ? 'border-gold-400 bg-gold-400' : 'border-gray-600'
                      }`}>
                        {selectedService?.id === s.id && <div className="w-2 h-2 rounded-full bg-gray-900" />}
                      </div>
                      <div className="flex-1">
                        <div className="font-semibold">{s.name}</div>
                        {s.description && <div className="text-sm text-gray-500 mt-0.5">{s.description}</div>}
                      </div>
                      <div className="text-right shrink-0">
                        <div className="text-gold-400 font-bold">{formatPrice(s.price)}</div>
                        <div className="text-xs text-gray-500 flex items-center gap-1 justify-end mt-0.5">
                          <Clock className="w-3 h-3" /> {s.duration_minutes} min
                        </div>
                      </div>
                    </button>
                  ))}
                </div>
              </div>
            ))}
          </div>
        )}

        {/* Step 1: Barber */}
        {step === 1 && (
          <div className="animate-slide-up space-y-4">
            <h2 className="text-xl font-bold">Elige tu barbero</h2>
            {eligibleBarbers.length === 0 ? (
              <div className="text-center py-10 text-gray-500">
                <Scissors className="w-12 h-12 mx-auto mb-3 opacity-30" />
                <p>No hay barberos disponibles para este servicio</p>
              </div>
            ) : (
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {eligibleBarbers.map((b) => (
                  <button
                    key={b.id}
                    onClick={() => { setSelectedBarber(b); setSelectedDate(''); setSelectedTime('') }}
                    className={`flex items-center gap-4 p-5 rounded-xl border text-left transition-all ${
                      selectedBarber?.id === b.id
                        ? 'border-gold-500 bg-gold-500/10'
                        : 'border-gray-800 hover:border-gray-700 bg-gray-950'
                    }`}
                  >
                    <Image
                      src={b.profile?.avatar_url || getAvatarUrl(b.profile?.full_name || 'B')}
                      alt={b.profile?.full_name || ''}
                      width={52}
                      height={52}
                      className="rounded-full border border-gray-700 shrink-0"
                    />
                    <div className="flex-1 min-w-0">
                      <div className="font-bold truncate">{b.profile?.full_name}</div>
                      {b.specialties?.length > 0 && (
                        <div className="text-xs text-gray-500 mt-0.5 truncate">
                          {b.specialties.join(' · ')}
                        </div>
                      )}
                      <div className="flex items-center gap-1 mt-1 text-gold-400">
                        <Star className="w-3 h-3 fill-current" />
                        <span className="text-xs font-medium">Experto</span>
                      </div>
                    </div>
                    {selectedBarber?.id === b.id && (
                      <CheckCircle className="w-5 h-5 text-gold-400 shrink-0" />
                    )}
                  </button>
                ))}
              </div>
            )}
          </div>
        )}

        {/* Step 2: Date & Time */}
        {step === 2 && (
          <div className="animate-slide-up space-y-6">
            <h2 className="text-xl font-bold">Selecciona fecha y hora</h2>

            {/* Date picker */}
            <div>
              <h3 className="text-sm font-medium text-gray-400 mb-3">Fecha disponible</h3>
              {availableDates.length === 0 ? (
                <div className="text-center py-8 text-gray-500 bg-gray-950 rounded-xl border border-gray-800">
                  <Calendar className="w-10 h-10 mx-auto mb-2 opacity-30" />
                  <p className="text-sm">Este barbero no tiene días disponibles</p>
                </div>
              ) : (
                <div className="grid grid-cols-4 sm:grid-cols-7 gap-2 max-h-48 overflow-y-auto pr-1">
                  {availableDates.slice(0, 21).map((date) => {
                    const dateStr = format(date, 'yyyy-MM-dd')
                    const isSelected = selectedDate === dateStr
                    return (
                      <button
                        key={dateStr}
                        onClick={() => { setSelectedDate(dateStr); setSelectedTime('') }}
                        className={`flex flex-col items-center p-2.5 rounded-xl border transition-all ${
                          isSelected
                            ? 'border-gold-500 bg-gold-500/15 text-gold-400'
                            : 'border-gray-800 hover:border-gray-700 bg-gray-950'
                        }`}
                      >
                        <span className="text-xs text-gray-500">
                          {format(date, 'EEE', { locale: es }).slice(0, 3)}
                        </span>
                        <span className="text-lg font-black">{format(date, 'd')}</span>
                        <span className="text-xs text-gray-500">
                          {format(date, 'MMM', { locale: es })}
                        </span>
                      </button>
                    )
                  })}
                </div>
              )}
            </div>

            {/* Time slots */}
            {selectedDate && (
              <div>
                <h3 className="text-sm font-medium text-gray-400 mb-3">
                  Horarios disponibles — {new Date(selectedDate + 'T00:00:00').toLocaleDateString('es-MX', { weekday: 'long', day: 'numeric', month: 'long' })}
                </h3>
                {timeSlots.length === 0 ? (
                  <div className="text-center py-6 text-gray-500 bg-gray-950 rounded-xl border border-gray-800">
                    <p className="text-sm">Sin horarios disponibles para este día</p>
                  </div>
                ) : (
                  <div className="grid grid-cols-3 sm:grid-cols-4 gap-2">
                    {timeSlots.map((t) => (
                      <button
                        key={t}
                        onClick={() => setSelectedTime(t)}
                        className={`py-3 rounded-xl border text-sm font-medium transition-all ${
                          selectedTime === t
                            ? 'border-gold-500 bg-gold-500/15 text-gold-400'
                            : 'border-gray-800 hover:border-gray-700 bg-gray-950 text-gray-300'
                        }`}
                      >
                        {formatTime(t)}
                      </button>
                    ))}
                  </div>
                )}
              </div>
            )}

            {/* Notes */}
            {selectedTime && (
              <div className="animate-slide-up">
                <label className="block text-sm font-medium text-gray-400 mb-2">
                  Notas adicionales <span className="text-gray-600">(opcional)</span>
                </label>
                <textarea
                  value={notes}
                  onChange={(e) => setNotes(e.target.value)}
                  rows={3}
                  className="input-dark resize-none"
                  placeholder="Alguna preferencia especial, alergia, o comentario..."
                />
              </div>
            )}
          </div>
        )}

        {/* Step 3: Confirm */}
        {step === 3 && (
          <div className="animate-slide-up space-y-6">
            <h2 className="text-xl font-bold">Confirma tu reserva</h2>

            <div className="space-y-3">
              <div className="flex items-center gap-4 p-4 bg-gray-950 rounded-xl border border-gray-800">
                <Scissors className="w-5 h-5 text-gold-400 shrink-0" />
                <div>
                  <div className="text-xs text-gray-500">Servicio</div>
                  <div className="font-semibold">{selectedService?.name}</div>
                </div>
                <div className="ml-auto text-gold-400 font-bold">{formatPrice(selectedService?.price || 0)}</div>
              </div>

              <div className="flex items-center gap-4 p-4 bg-gray-950 rounded-xl border border-gray-800">
                <User className="w-5 h-5 text-gold-400 shrink-0" />
                <div>
                  <div className="text-xs text-gray-500">Barbero</div>
                  <div className="font-semibold">{selectedBarber?.profile?.full_name}</div>
                </div>
              </div>

              <div className="flex items-center gap-4 p-4 bg-gray-950 rounded-xl border border-gray-800">
                <Calendar className="w-5 h-5 text-gold-400 shrink-0" />
                <div>
                  <div className="text-xs text-gray-500">Fecha</div>
                  <div className="font-semibold">
                    {selectedDate && new Date(selectedDate + 'T00:00:00').toLocaleDateString('es-MX', {
                      weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
                    })}
                  </div>
                </div>
              </div>

              <div className="flex items-center gap-4 p-4 bg-gray-950 rounded-xl border border-gray-800">
                <Clock className="w-5 h-5 text-gold-400 shrink-0" />
                <div>
                  <div className="text-xs text-gray-500">Hora</div>
                  <div className="font-semibold">
                    {selectedTime && formatTime(selectedTime)} — {selectedService?.duration_minutes} min
                  </div>
                </div>
              </div>

              {notes && (
                <div className="p-4 bg-gray-950 rounded-xl border border-gray-800">
                  <div className="text-xs text-gray-500 mb-1">Notas</div>
                  <div className="text-sm text-gray-300">{notes}</div>
                </div>
              )}
            </div>

            <div className="p-4 bg-gold-900/20 border border-gold-800/30 rounded-xl">
              <div className="flex items-center justify-between">
                <span className="font-semibold">Total a pagar</span>
                <span className="text-2xl font-black text-gold-400">{formatPrice(selectedService?.price || 0)}</span>
              </div>
              <p className="text-xs text-gray-500 mt-1">Pago en el local el día de tu cita</p>
            </div>
          </div>
        )}
      </div>

      {/* Navigation */}
      <div className="border-t border-gray-800 p-6 flex items-center justify-between bg-gray-950">
        <button
          onClick={() => setStep((s) => s - 1)}
          disabled={step === 0}
          className="btn-secondary inline-flex items-center gap-2 disabled:opacity-0"
        >
          <ChevronLeft className="w-4 h-4" /> Atrás
        </button>

        {step < 3 ? (
          <button
            onClick={() => setStep((s) => s + 1)}
            disabled={
              (step === 0 && !selectedService) ||
              (step === 1 && !selectedBarber) ||
              (step === 2 && (!selectedDate || !selectedTime))
            }
            className="btn-primary inline-flex items-center gap-2 disabled:opacity-50"
          >
            Continuar <ChevronRight className="w-4 h-4" />
          </button>
        ) : (
          <button
            onClick={handleBook}
            disabled={loading}
            className="btn-primary inline-flex items-center gap-2"
          >
            {loading ? (
              <div className="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin" />
            ) : (
              <>
                <CheckCircle className="w-4 h-4" />
                Confirmar reserva
              </>
            )}
          </button>
        )}
      </div>
    </div>
  )
}
