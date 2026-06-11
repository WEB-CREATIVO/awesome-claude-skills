import { createClient } from '@/lib/supabase/server'
import { redirect } from 'next/navigation'
import BarbersClient from './BarbersClient'

export default async function BarbersPage() {
  const supabase = await createClient()
  const { data: { user } } = await supabase.auth.getUser()
  if (!user) redirect('/login')

  const { data: profile } = await supabase.from('profiles').select('*').eq('id', user.id).single()
  if (profile?.role !== 'admin') redirect('/dashboard')

  const { data: barbers } = await supabase
    .from('barbers')
    .select('*, profile:profiles(*)')
    .order('created_at', { ascending: false })

  const { data: profiles } = await supabase
    .from('profiles')
    .select('*')
    .eq('role', 'barber')
    .order('full_name')

  const { data: services } = await supabase.from('services').select('*').eq('is_active', true)
  const { data: barberServices } = await supabase.from('barber_services').select('*')
  const { data: workingHours } = await supabase.from('working_hours').select('*')

  return (
    <BarbersClient
      barbers={barbers || []}
      profiles={profiles || []}
      services={services || []}
      barberServices={barberServices || []}
      workingHours={workingHours || []}
    />
  )
}
