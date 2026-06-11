import { createClient } from '@/lib/supabase/server'
import { redirect } from 'next/navigation'
import { Calendar, Users, DollarSign, Clock, TrendingUp, CheckCircle, AlertCircle, XCircle } from 'lucide-react'
import { formatPrice, formatDate, formatTime, STATUS_COLORS, STATUS_LABELS } from '@/lib/utils'
import type { Appointment } from '@/lib/types'
import Link from 'next/link'

function StatCard({ icon: Icon, label, value, sub, color = 'gold' }: {
  icon: React.ElementType
  label: string
  value: string | number
  sub?: string
  color?: string
}) {
  const colorMap: Record<string, string> = {
    gold: 'bg-gold-500/10 text-gold-400',
    blue: 'bg-blue-500/10 text-blue-400',
    green: 'bg-green-500/10 text-green-400',
    red: 'bg-red-500/10 text-red-400',
  }
  return (
    <div className="card p-6">
      <div className="flex items-start justify-between mb-4">
        <div className={`w-10 h-10 rounded-xl flex items-center justify-center ${colorMap[color]}`}>
          <Icon className="w-5 h-5" />
        </div>
      </div>
      <div className="text-3xl font-black mb-1">{value}</div>
      <div className="text-sm text-gray-400">{label}</div>
      {sub && <div className="text-xs text-gray-600 mt-1">{sub}</div>}
    </div>
  )
}

export default async function DashboardPage() {
  const supabase = await createClient()
  const { data: { user } } = await supabase.auth.getUser()
  if (!user) redirect('/login')

  const { data: profile } = await supabase.from('profiles').select('*').eq('id', user.id).single()
  if (!profile) redirect('/login')

  const today = new Date().toISOString().split('T')[0]

  let appointments: Appointment[] = []
  let stats = { total: 0, pending: 0, confirmed: 0, completed: 0, revenue: 0 }
  let totalClients = 0, totalBarbers = 0

  if (profile.role === 'admin') {
    const { data: appts } = await supabase
      .from('appointments')
      .select(`*, client:profiles!appointments_client_id_fkey(*),
               barber:barbers!appointments_barber_id_fkey(*, profile:profiles(*)),
               service:services(*)`)
      .order('appointment_date', { ascending: false })
      .order('start_time', { ascending: false })
      .limit(10)

    appointments = appts || []

    const { data: allAppts } = await supabase.from('appointments').select('status, total_price')
    if (allAppts) {
      stats.total = allAppts.length
      stats.pending = allAppts.filter((a) => a.status === 'pending').length
      stats.confirmed = allAppts.filter((a) => a.status === 'confirmed').length
      stats.completed = allAppts.filter((a) => a.status === 'completed').length
      stats.revenue = allAppts.filter((a) => a.status === 'completed').reduce((s, a) => s + a.total_price, 0)
    }

    const { count: clientCount } = await supabase.from('profiles').select('*', { count: 'exact', head: true }).eq('role', 'client')
    const { count: barberCount } = await supabase.from('barbers').select('*', { count: 'exact', head: true }).eq('is_active', true)
    totalClients = clientCount || 0
    totalBarbers = barberCount || 0

  } else if (profile.role === 'barber') {
    const { data: barber } = await supabase.from('barbers').select('id').eq('profile_id', user.id).single()
    if (barber) {
      const { data: appts } = await supabase
        .from('appointments')
        .select(`*, client:profiles!appointments_client_id_fkey(*), service:services(*)`)
        .eq('barber_id', barber.id)
        .order('appointment_date', { ascending: false })
        .limit(10)

      appointments = appts || []
      stats.total = appointments.length
      stats.pending = appointments.filter((a) => a.status === 'pending').length
      stats.confirmed = appointments.filter((a) => a.status === 'confirmed').length
      stats.completed = appointments.filter((a) => a.status === 'completed').length
      stats.revenue = appointments.filter((a) => a.status === 'completed').reduce((s, a) => s + a.total_price, 0)
    }
  } else {
    const { data: appts } = await supabase
      .from('appointments')
      .select(`*, barber:barbers!appointments_barber_id_fkey(*, profile:profiles(*)), service:services(*)`)
      .eq('client_id', user.id)
      .order('appointment_date', { ascending: false })
      .limit(10)

    appointments = appts || []
    stats.total = appointments.length
    stats.pending = appointments.filter((a) => a.status === 'pending').length
    stats.confirmed = appointments.filter((a) => a.status === 'confirmed').length
  }

  const todayAppts = appointments.filter((a) => a.appointment_date === today)
  const upcomingAppts = appointments.filter((a) => a.appointment_date >= today && a.status !== 'cancelled').slice(0, 5)

  return (
    <div className="space-y-8">
      {/* Header */}
      <div>
        <h1 className="text-3xl font-black">
          Hola, {profile.full_name.split(' ')[0]} 👋
        </h1>
        <p className="text-gray-400 mt-1">
          {new Date().toLocaleDateString('es-MX', { weekday: 'long', day: 'numeric', month: 'long' })}
        </p>
      </div>

      {/* Stats Grid */}
      <div className={`grid gap-4 ${profile.role === 'admin' ? 'grid-cols-2 lg:grid-cols-4' : 'grid-cols-2 lg:grid-cols-3'}`}>
        <StatCard icon={Calendar} label="Total citas" value={stats.total} color="gold" />
        <StatCard icon={Clock} label="Pendientes" value={stats.pending} color="blue" />
        <StatCard icon={CheckCircle} label="Completadas" value={stats.completed} color="green" />
        {profile.role !== 'client' && (
          <StatCard icon={DollarSign} label="Ingresos" value={formatPrice(stats.revenue)} color="gold" />
        )}
        {profile.role === 'admin' && (
          <>
            <StatCard icon={Users} label="Clientes" value={totalClients} color="blue" />
            <StatCard icon={TrendingUp} label="Barberos activos" value={totalBarbers} color="green" />
          </>
        )}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Today */}
        <div className="card p-6">
          <h2 className="font-bold text-lg mb-4 flex items-center gap-2">
            <Clock className="w-5 h-5 text-gold-400" />
            Hoy ({todayAppts.length})
          </h2>
          {todayAppts.length === 0 ? (
            <div className="text-center py-8 text-gray-500">
              <Calendar className="w-10 h-10 mx-auto mb-2 opacity-30" />
              <p className="text-sm">Sin citas hoy</p>
            </div>
          ) : (
            <div className="space-y-3">
              {todayAppts.map((apt) => (
                <div key={apt.id} className="p-3 bg-gray-950 rounded-xl border border-gray-800">
                  <div className="flex justify-between items-start">
                    <div>
                      <div className="font-medium text-sm">
                        {profile.role === 'client' ? apt.barber?.profile?.full_name : apt.client?.full_name}
                      </div>
                      <div className="text-xs text-gray-500 mt-0.5">{apt.service?.name}</div>
                    </div>
                    <div className="text-xs text-gold-400 font-medium">{formatTime(apt.start_time)}</div>
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>

        {/* Upcoming */}
        <div className="card p-6 lg:col-span-2">
          <div className="flex items-center justify-between mb-4">
            <h2 className="font-bold text-lg flex items-center gap-2">
              <Calendar className="w-5 h-5 text-gold-400" />
              Próximas citas
            </h2>
            <Link href="/dashboard/appointments" className="text-sm text-gold-400 hover:text-gold-300">
              Ver todas →
            </Link>
          </div>

          {upcomingAppts.length === 0 ? (
            <div className="text-center py-8 text-gray-500">
              <Calendar className="w-10 h-10 mx-auto mb-2 opacity-30" />
              <p className="text-sm">No hay citas próximas</p>
              <Link href="/book" className="btn-primary text-sm py-2 px-4 mt-4 inline-block">
                Reservar cita
              </Link>
            </div>
          ) : (
            <div className="space-y-3">
              {upcomingAppts.map((apt) => (
                <div key={apt.id} className="flex items-center gap-4 p-4 bg-gray-950 rounded-xl border border-gray-800 hover:border-gray-700 transition-all">
                  <div className="text-center min-w-[52px]">
                    <div className="text-xs text-gray-500">
                      {new Date(apt.appointment_date + 'T00:00:00').toLocaleDateString('es-MX', { month: 'short' })}
                    </div>
                    <div className="text-2xl font-black text-gold-400">
                      {new Date(apt.appointment_date + 'T00:00:00').getDate()}
                    </div>
                  </div>
                  <div className="flex-1 min-w-0">
                    <div className="font-medium text-sm">
                      {profile.role === 'client' ? apt.barber?.profile?.full_name : apt.client?.full_name}
                    </div>
                    <div className="text-xs text-gray-500">
                      {apt.service?.name} · {formatTime(apt.start_time)}
                    </div>
                  </div>
                  <div className="flex flex-col items-end gap-1">
                    <span className={`text-xs px-2 py-0.5 rounded-full border ${STATUS_COLORS[apt.status]}`}>
                      {STATUS_LABELS[apt.status]}
                    </span>
                    <span className="text-xs text-gold-400">{formatPrice(apt.total_price)}</span>
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>
      </div>
    </div>
  )
}
