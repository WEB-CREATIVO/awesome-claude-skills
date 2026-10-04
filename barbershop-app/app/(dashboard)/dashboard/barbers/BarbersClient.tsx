'use client'
import { useState } from 'react'
import { createClient } from '@/lib/supabase/client'
import { useRouter } from 'next/navigation'
import { Users, Plus, X, Edit2, CheckCircle, Clock } from 'lucide-react'
import type { Barber, Service, Profile } from '@/lib/types'
import { getAvatarUrl, DAY_NAMES } from '@/lib/utils'
import Image from 'next/image'

const DEFAULT_HOURS = DAY_NAMES.map((_, i) => ({
  day_of_week: i,
  start_time: '09:00',
  end_time: '18:00',
  is_active: i > 0 && i < 6,
}))

export default function BarbersClient({
  barbers, profiles, services, barberServices, workingHours,
}: {
  barbers: (Barber & { profile: Profile })[]
  profiles: Profile[]
  services: Service[]
  barberServices: { barber_id: string; service_id: string }[]
  workingHours: { barber_id: string; day_of_week: number; start_time: string; end_time: string; is_active: boolean }[]
}) {
  const router = useRouter()
  const [showModal, setShowModal] = useState(false)
  const [editBarber, setEditBarber] = useState<(Barber & { profile: Profile }) | null>(null)
  const [selectedProfileId, setSelectedProfileId] = useState('')
  const [bio, setBio] = useState('')
  const [specialties, setSpecialties] = useState('')
  const [selectedServices, setSelectedServices] = useState<string[]>([])
  const [hours, setHours] = useState(DEFAULT_HOURS)
  const [loading, setLoading] = useState(false)

  const openCreate = () => {
    setEditBarber(null)
    setSelectedProfileId('')
    setBio('')
    setSpecialties('')
    setSelectedServices([])
    setHours(DEFAULT_HOURS.map((h) => ({ ...h })))
    setShowModal(true)
  }

  const openEdit = (b: Barber & { profile: Profile }) => {
    setEditBarber(b)
    setSelectedProfileId(b.profile_id)
    setBio(b.bio || '')
    setSpecialties(b.specialties?.join(', ') || '')
    setSelectedServices(barberServices.filter((bs) => bs.barber_id === b.id).map((bs) => bs.service_id))
    const bh = workingHours.filter((wh) => wh.barber_id === b.id)
    setHours(DEFAULT_HOURS.map((dh) => {
      const found = bh.find((wh) => wh.day_of_week === dh.day_of_week)
      return found ? { ...found } : { ...dh }
    }))
    setShowModal(true)
  }

  const toggleService = (id: string) =>
    setSelectedServices((prev) => prev.includes(id) ? prev.filter((s) => s !== id) : [...prev, id])

  const updateHour = (idx: number, field: string, value: string | boolean) =>
    setHours((prev) => prev.map((h, i) => i === idx ? { ...h, [field]: value } : h))

  const handleSave = async () => {
    if (!selectedProfileId) return
    setLoading(true)
    const supabase = createClient()

    let barberId = editBarber?.id

    if (!editBarber) {
      const { data, error } = await supabase
        .from('barbers')
        .insert({ profile_id: selectedProfileId, bio, specialties: specialties.split(',').map((s) => s.trim()).filter(Boolean) })
        .select()
        .single()
      if (error || !data) { setLoading(false); return }
      barberId = data.id
    } else {
      await supabase.from('barbers').update({
        bio, specialties: specialties.split(',').map((s) => s.trim()).filter(Boolean),
      }).eq('id', editBarber.id)
    }

    if (barberId) {
      await supabase.from('barber_services').delete().eq('barber_id', barberId)
      if (selectedServices.length > 0) {
        await supabase.from('barber_services').insert(
          selectedServices.map((sid) => ({ barber_id: barberId, service_id: sid }))
        )
      }

      for (const h of hours) {
        await supabase.from('working_hours').upsert({
          barber_id: barberId,
          day_of_week: h.day_of_week,
          start_time: h.start_time,
          end_time: h.end_time,
          is_active: h.is_active,
        }, { onConflict: 'barber_id,day_of_week' })
      }
    }

    setShowModal(false)
    setLoading(false)
    router.refresh()
  }

  const toggleActive = async (b: Barber) => {
    const supabase = createClient()
    await supabase.from('barbers').update({ is_active: !b.is_active }).eq('id', b.id)
    router.refresh()
  }

  const availableProfiles = editBarber
    ? profiles
    : profiles.filter((p) => !barbers.some((b) => b.profile_id === p.id))

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-black">Barberos</h1>
          <p className="text-gray-400 mt-1">{barbers.length} registrados</p>
        </div>
        <button onClick={openCreate} className="btn-primary inline-flex items-center gap-2">
          <Plus className="w-4 h-4" /> Añadir barbero
        </button>
      </div>

      {barbers.length === 0 ? (
        <div className="card p-16 text-center">
          <Users className="w-14 h-14 mx-auto text-gray-700 mb-4" />
          <h3 className="text-lg font-semibold text-gray-400">Sin barberos registrados</h3>
          <p className="text-gray-600 text-sm mt-1">Primero crea usuarios con rol "barbero" y luego añádelos aquí</p>
        </div>
      ) : (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          {barbers.map((b) => {
            const svcNames = barberServices
              .filter((bs) => bs.barber_id === b.id)
              .map((bs) => services.find((s) => s.id === bs.service_id)?.name)
              .filter(Boolean)

            return (
              <div key={b.id} className={`card p-6 transition-all ${!b.is_active ? 'opacity-60' : 'hover:border-gray-700'}`}>
                <div className="flex items-start gap-4 mb-4">
                  <Image
                    src={b.profile?.avatar_url || getAvatarUrl(b.profile?.full_name || 'B')}
                    alt={b.profile?.full_name || ''}
                    width={48} height={48}
                    className="rounded-full border border-gray-700"
                  />
                  <div className="flex-1">
                    <div className="font-bold">{b.profile?.full_name}</div>
                    <div className="text-xs text-gray-500">{b.profile?.phone || 'Sin teléfono'}</div>
                    <span className={`inline-flex items-center gap-1 text-xs mt-1 px-2 py-0.5 rounded-full ${b.is_active ? 'bg-green-500/10 text-green-400' : 'bg-gray-800 text-gray-500'}`}>
                      <span className={`w-1.5 h-1.5 rounded-full ${b.is_active ? 'bg-green-400' : 'bg-gray-600'}`} />
                      {b.is_active ? 'Activo' : 'Inactivo'}
                    </span>
                  </div>
                </div>

                {b.bio && <p className="text-sm text-gray-400 mb-3 line-clamp-2">{b.bio}</p>}

                {svcNames.length > 0 && (
                  <div className="flex flex-wrap gap-1.5 mb-4">
                    {svcNames.map((name) => (
                      <span key={name} className="text-xs px-2 py-0.5 bg-gray-800 rounded-full text-gray-400">
                        {name}
                      </span>
                    ))}
                  </div>
                )}

                <div className="flex gap-2 pt-4 border-t border-gray-800">
                  <button
                    onClick={() => openEdit(b)}
                    className="flex-1 flex items-center justify-center gap-2 py-2 text-sm bg-gray-800 hover:bg-gray-700 rounded-xl transition-all"
                  >
                    <Edit2 className="w-3.5 h-3.5" /> Editar
                  </button>
                  <button
                    onClick={() => toggleActive(b)}
                    className={`flex-1 flex items-center justify-center gap-2 py-2 text-sm rounded-xl transition-all ${
                      b.is_active
                        ? 'bg-red-900/30 text-red-400 hover:bg-red-900/50'
                        : 'bg-green-900/30 text-green-400 hover:bg-green-900/50'
                    }`}
                  >
                    <CheckCircle className="w-3.5 h-3.5" />
                    {b.is_active ? 'Desactivar' : 'Activar'}
                  </button>
                </div>
              </div>
            )
          })}
        </div>
      )}

      {/* Modal */}
      {showModal && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
          <div className="card w-full max-w-2xl max-h-[90vh] overflow-y-auto animate-slide-up">
            <div className="flex items-center justify-between p-6 border-b border-gray-800">
              <h2 className="text-xl font-black">{editBarber ? 'Editar barbero' : 'Añadir barbero'}</h2>
              <button onClick={() => setShowModal(false)} className="p-2 hover:bg-gray-800 rounded-xl">
                <X className="w-5 h-5" />
              </button>
            </div>

            <div className="p-6 space-y-6">
              {/* Profile */}
              <div>
                <label className="block text-sm font-medium text-gray-300 mb-2">Perfil de usuario</label>
                <select
                  value={selectedProfileId}
                  onChange={(e) => setSelectedProfileId(e.target.value)}
                  disabled={!!editBarber}
                  className="input-dark"
                >
                  <option value="">Selecciona un usuario con rol barbero</option>
                  {availableProfiles.map((p) => (
                    <option key={p.id} value={p.id}>{p.full_name}</option>
                  ))}
                </select>
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-300 mb-2">Biografía</label>
                <textarea
                  value={bio}
                  onChange={(e) => setBio(e.target.value)}
                  rows={3}
                  className="input-dark resize-none"
                  placeholder="Descripción del barbero..."
                />
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-300 mb-2">Especialidades (separadas por coma)</label>
                <input
                  type="text"
                  value={specialties}
                  onChange={(e) => setSpecialties(e.target.value)}
                  className="input-dark"
                  placeholder="Fade, Barba, Diseño"
                />
              </div>

              {/* Services */}
              <div>
                <label className="block text-sm font-medium text-gray-300 mb-3">Servicios que realiza</label>
                <div className="grid grid-cols-2 gap-2">
                  {services.map((s) => (
                    <label
                      key={s.id}
                      className={`flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all ${
                        selectedServices.includes(s.id)
                          ? 'border-gold-500 bg-gold-500/10'
                          : 'border-gray-700 hover:border-gray-600'
                      }`}
                    >
                      <input
                        type="checkbox"
                        checked={selectedServices.includes(s.id)}
                        onChange={() => toggleService(s.id)}
                        className="sr-only"
                      />
                      <div className={`w-4 h-4 rounded border-2 flex items-center justify-center shrink-0 ${
                        selectedServices.includes(s.id) ? 'border-gold-400 bg-gold-400' : 'border-gray-600'
                      }`}>
                        {selectedServices.includes(s.id) && (
                          <svg className="w-2.5 h-2.5 text-gray-900" fill="currentColor" viewBox="0 0 20 20">
                            <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                          </svg>
                        )}
                      </div>
                      <div>
                        <div className="text-sm font-medium">{s.name}</div>
                        <div className="text-xs text-gray-500">{s.duration_minutes} min</div>
                      </div>
                    </label>
                  ))}
                </div>
              </div>

              {/* Working Hours */}
              <div>
                <label className="block text-sm font-medium text-gray-300 mb-3 flex items-center gap-2">
                  <Clock className="w-4 h-4" />
                  Horario de trabajo
                </label>
                <div className="space-y-2">
                  {hours.map((h, i) => (
                    <div key={i} className={`flex items-center gap-3 p-3 rounded-xl border ${h.is_active ? 'border-gray-700 bg-gray-950' : 'border-gray-800 opacity-50'}`}>
                      <input
                        type="checkbox"
                        checked={h.is_active}
                        onChange={(e) => updateHour(i, 'is_active', e.target.checked)}
                        className="accent-amber-500"
                      />
                      <span className="text-sm w-20 font-medium">{DAY_NAMES[i]}</span>
                      <input
                        type="time"
                        value={h.start_time}
                        onChange={(e) => updateHour(i, 'start_time', e.target.value)}
                        disabled={!h.is_active}
                        className="input-dark py-1.5 px-3 text-sm w-auto"
                      />
                      <span className="text-gray-600">–</span>
                      <input
                        type="time"
                        value={h.end_time}
                        onChange={(e) => updateHour(i, 'end_time', e.target.value)}
                        disabled={!h.is_active}
                        className="input-dark py-1.5 px-3 text-sm w-auto"
                      />
                    </div>
                  ))}
                </div>
              </div>
            </div>

            <div className="flex gap-3 p-6 border-t border-gray-800">
              <button onClick={() => setShowModal(false)} className="btn-secondary flex-1">
                Cancelar
              </button>
              <button onClick={handleSave} disabled={loading || !selectedProfileId} className="btn-primary flex-1">
                {loading ? <div className="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin mx-auto" /> : 'Guardar'}
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  )
}
