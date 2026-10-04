'use client'
import { useState } from 'react'
import { createClient } from '@/lib/supabase/client'
import { useRouter } from 'next/navigation'
import { Calendar, Filter, Search, CheckCircle, XCircle, Clock, Plus } from 'lucide-react'
import { formatPrice, formatDate, formatTime, STATUS_COLORS, STATUS_LABELS } from '@/lib/utils'
import type { Appointment, AppointmentStatus, UserRole } from '@/lib/types'
import Link from 'next/link'

const STATUS_OPTIONS: { value: string; label: string }[] = [
  { value: 'all', label: 'Todas' },
  { value: 'pending', label: 'Pendientes' },
  { value: 'confirmed', label: 'Confirmadas' },
  { value: 'completed', label: 'Completadas' },
  { value: 'cancelled', label: 'Canceladas' },
]

export default function AppointmentsClient({
  appointments,
  role,
  userId,
}: {
  appointments: Appointment[]
  role: UserRole
  userId: string
}) {
  const router = useRouter()
  const [statusFilter, setStatusFilter] = useState('all')
  const [search, setSearch] = useState('')
  const [loading, setLoading] = useState<string | null>(null)

  const filtered = appointments.filter((a) => {
    if (statusFilter !== 'all' && a.status !== statusFilter) return false
    const q = search.toLowerCase()
    if (!q) return true
    return (
      a.service?.name?.toLowerCase().includes(q) ||
      a.client?.full_name?.toLowerCase().includes(q) ||
      a.barber?.profile?.full_name?.toLowerCase().includes(q) ||
      a.appointment_date.includes(q)
    )
  })

  const updateStatus = async (id: string, status: AppointmentStatus) => {
    setLoading(id + status)
    const supabase = createClient()
    await supabase.from('appointments').update({ status }).eq('id', id)
    router.refresh()
    setLoading(null)
  }

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-black">Citas</h1>
          <p className="text-gray-400 mt-1">{filtered.length} resultados</p>
        </div>
        <Link href="/book" className="btn-primary inline-flex items-center gap-2">
          <Plus className="w-4 h-4" />
          Nueva cita
        </Link>
      </div>

      {/* Filters */}
      <div className="flex items-center gap-3 flex-wrap">
        <div className="relative flex-1 min-w-[200px] max-w-xs">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" />
          <input
            type="text"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            placeholder="Buscar..."
            className="input-dark pl-10 py-2 text-sm"
          />
        </div>

        <div className="flex items-center gap-2 flex-wrap">
          <Filter className="w-4 h-4 text-gray-500" />
          {STATUS_OPTIONS.map((o) => (
            <button
              key={o.value}
              onClick={() => setStatusFilter(o.value)}
              className={`px-3 py-1.5 rounded-lg text-sm transition-all ${
                statusFilter === o.value
                  ? 'bg-gold-600/20 text-gold-400 border border-gold-600/30'
                  : 'bg-gray-900 text-gray-400 border border-gray-800 hover:border-gray-700'
              }`}
            >
              {o.label}
            </button>
          ))}
        </div>
      </div>

      {/* Table */}
      {filtered.length === 0 ? (
        <div className="card p-16 text-center">
          <Calendar className="w-14 h-14 mx-auto text-gray-700 mb-4" />
          <h3 className="text-lg font-semibold text-gray-400">No hay citas</h3>
          <p className="text-gray-600 text-sm mt-1">Ajusta los filtros o reserva una nueva cita</p>
        </div>
      ) : (
        <div className="card overflow-hidden">
          <div className="overflow-x-auto">
            <table className="w-full">
              <thead>
                <tr className="border-b border-gray-800 bg-gray-950/50">
                  <th className="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha / Hora</th>
                  {role !== 'client' && <th className="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Cliente</th>}
                  {role !== 'barber' && <th className="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Barbero</th>}
                  <th className="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Servicio</th>
                  <th className="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                  <th className="text-right px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                  <th className="text-right px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-gray-800">
                {filtered.map((apt) => (
                  <tr key={apt.id} className="hover:bg-gray-900/50 transition-colors">
                    <td className="px-6 py-4">
                      <div className="font-medium text-sm">
                        {new Date(apt.appointment_date + 'T00:00:00').toLocaleDateString('es-MX', {
                          day: 'numeric', month: 'short', year: 'numeric'
                        })}
                      </div>
                      <div className="text-xs text-gray-500">{formatTime(apt.start_time)} – {formatTime(apt.end_time)}</div>
                    </td>
                    {role !== 'client' && (
                      <td className="px-6 py-4">
                        <div className="text-sm font-medium">{apt.client?.full_name}</div>
                        <div className="text-xs text-gray-500">{apt.client?.phone || '—'}</div>
                      </td>
                    )}
                    {role !== 'barber' && (
                      <td className="px-6 py-4">
                        <div className="text-sm">{apt.barber?.profile?.full_name || '—'}</div>
                      </td>
                    )}
                    <td className="px-6 py-4">
                      <div className="text-sm">{apt.service?.name}</div>
                      <div className="text-xs text-gray-500">{apt.service?.duration_minutes} min</div>
                    </td>
                    <td className="px-6 py-4">
                      <span className={`inline-flex text-xs px-2.5 py-1 rounded-full border ${STATUS_COLORS[apt.status]}`}>
                        {STATUS_LABELS[apt.status]}
                      </span>
                    </td>
                    <td className="px-6 py-4 text-right">
                      <span className="text-gold-400 font-semibold">{formatPrice(apt.total_price)}</span>
                    </td>
                    <td className="px-6 py-4">
                      <div className="flex items-center justify-end gap-2">
                        {(role === 'admin' || role === 'barber') && apt.status === 'pending' && (
                          <button
                            onClick={() => updateStatus(apt.id, 'confirmed')}
                            disabled={!!loading}
                            className="p-1.5 text-blue-400 hover:text-blue-300 hover:bg-blue-400/10 rounded-lg transition-all"
                            title="Confirmar"
                          >
                            <CheckCircle className="w-4 h-4" />
                          </button>
                        )}
                        {(role === 'admin' || role === 'barber') && apt.status === 'confirmed' && (
                          <button
                            onClick={() => updateStatus(apt.id, 'completed')}
                            disabled={!!loading}
                            className="p-1.5 text-green-400 hover:text-green-300 hover:bg-green-400/10 rounded-lg transition-all"
                            title="Completar"
                          >
                            <CheckCircle className="w-4 h-4" />
                          </button>
                        )}
                        {apt.status !== 'cancelled' && apt.status !== 'completed' &&
                          (role === 'admin' || apt.client_id === userId) && (
                          <button
                            onClick={() => updateStatus(apt.id, 'cancelled')}
                            disabled={!!loading}
                            className="p-1.5 text-red-400 hover:text-red-300 hover:bg-red-400/10 rounded-lg transition-all"
                            title="Cancelar"
                          >
                            <XCircle className="w-4 h-4" />
                          </button>
                        )}
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}
    </div>
  )
}
