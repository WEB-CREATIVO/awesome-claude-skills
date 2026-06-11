import { createClient } from '@/lib/supabase/server'
import { redirect } from 'next/navigation'
import ServicesClient from './ServicesClient'

export default async function ServicesPage() {
  const supabase = await createClient()
  const { data: { user } } = await supabase.auth.getUser()
  if (!user) redirect('/login')

  const { data: profile } = await supabase.from('profiles').select('*').eq('id', user.id).single()
  if (profile?.role !== 'admin') redirect('/dashboard')

  const { data: services } = await supabase
    .from('services')
    .select('*')
    .order('category')
    .order('name')

  return <ServicesClient services={services || []} />
}
