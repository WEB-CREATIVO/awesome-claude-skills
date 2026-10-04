import { createClient } from '@/lib/supabase/server'
import { redirect } from 'next/navigation'
import AppointmentsClient from './AppointmentsClient'

export default async function AppointmentsPage() {
  const supabase = await createClient()
  const { data: { user } } = await supabase.auth.getUser()
  if (!user) redirect('/login')

  const { data: profile } = await supabase.from('profiles').select('*').eq('id', user.id).single()
  if (!profile) redirect('/login')

  let appointments = []

  if (profile.role === 'admin') {
    const { data } = await supabase
      .from('appointments')
      .select(`*, client:profiles!appointments_client_id_fkey(*),
               barber:barbers!appointments_barber_id_fkey(*, profile:profiles(*)),
               service:services(*)`)
      .order('appointment_date', { ascending: false })
      .order('start_time', { ascending: false })
    appointments = data || []
  } else if (profile.role === 'barber') {
    const { data: barber } = await supabase.from('barbers').select('id').eq('profile_id', user.id).single()
    if (barber) {
      const { data } = await supabase
        .from('appointments')
        .select(`*, client:profiles!appointments_client_id_fkey(*), service:services(*)`)
        .eq('barber_id', barber.id)
        .order('appointment_date', { ascending: false })
      appointments = data || []
    }
  } else {
    const { data } = await supabase
      .from('appointments')
      .select(`*, barber:barbers!appointments_barber_id_fkey(*, profile:profiles(*)), service:services(*)`)
      .eq('client_id', user.id)
      .order('appointment_date', { ascending: false })
    appointments = data || []
  }

  return <AppointmentsClient appointments={appointments} role={profile.role} userId={user.id} />
}
