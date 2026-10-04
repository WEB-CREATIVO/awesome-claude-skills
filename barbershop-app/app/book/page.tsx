import { createClient } from '@/lib/supabase/server'
import BookingWizard from './BookingWizard'
import Link from 'next/link'
import { Scissors } from 'lucide-react'

export default async function BookPage() {
  const supabase = await createClient()

  const { data: services } = await supabase
    .from('services')
    .select('*')
    .eq('is_active', true)
    .order('category')
    .order('name')

  const { data: barbers } = await supabase
    .from('barbers')
    .select('*, profile:profiles(*)')
    .eq('is_active', true)

  const { data: barberServices } = await supabase.from('barber_services').select('*')
  const { data: workingHours } = await supabase.from('working_hours').select('*').eq('is_active', true)

  const { data: { user } } = await supabase.auth.getUser()

  return (
    <div className="min-h-screen bg-[rgb(15,15,20)]">
      <nav className="border-b border-gray-800 px-6 h-14 flex items-center justify-between">
        <Link href="/" className="flex items-center gap-2">
          <div className="w-7 h-7 gold-gradient rounded-lg flex items-center justify-center">
            <Scissors className="w-3.5 h-3.5 text-white" />
          </div>
          <span className="font-bold">BarberPro</span>
        </Link>
        {user ? (
          <Link href="/dashboard" className="text-sm text-gray-400 hover:text-white transition-colors">
            Mi panel →
          </Link>
        ) : (
          <Link href="/login" className="text-sm text-gray-400 hover:text-white transition-colors">
            Iniciar sesión
          </Link>
        )}
      </nav>

      <div className="max-w-3xl mx-auto px-6 py-12">
        <div className="text-center mb-10">
          <h1 className="text-4xl font-black mb-2">Reserva tu cita</h1>
          <p className="text-gray-400">Elige tu servicio, barbero y horario</p>
        </div>

        <BookingWizard
          services={services || []}
          barbers={barbers || []}
          barberServices={barberServices || []}
          workingHours={workingHours || []}
          userId={user?.id}
        />
      </div>
    </div>
  )
}
