'use client'
import Link from 'next/link'
import { usePathname } from 'next/navigation'
import { createClient } from '@/lib/supabase/client'
import { useRouter } from 'next/navigation'
import {
  Scissors, LayoutDashboard, Calendar, Users, Wrench,
  UserCircle, LogOut, ChevronRight,
} from 'lucide-react'
import type { Profile } from '@/lib/types'
import { getAvatarUrl } from '@/lib/utils'
import Image from 'next/image'

const navItems = [
  { href: '/dashboard',             icon: LayoutDashboard, label: 'Inicio',    roles: ['admin','barber','client'] },
  { href: '/dashboard/appointments', icon: Calendar,        label: 'Citas',     roles: ['admin','barber','client'] },
  { href: '/dashboard/barbers',      icon: Users,           label: 'Barberos',  roles: ['admin'] },
  { href: '/dashboard/services',     icon: Wrench,          label: 'Servicios', roles: ['admin'] },
  { href: '/dashboard/profile',      icon: UserCircle,      label: 'Mi perfil', roles: ['admin','barber','client'] },
]

const ROLE_LABEL: Record<string, string> = {
  admin: 'Administrador',
  barber: 'Barbero',
  client: 'Cliente',
}

export default function Sidebar({ profile }: { profile: Profile }) {
  const pathname = usePathname()
  const router = useRouter()

  const handleLogout = async () => {
    const supabase = createClient()
    await supabase.auth.signOut()
    router.push('/')
    router.refresh()
  }

  const filtered = navItems.filter((item) => item.roles.includes(profile.role))

  return (
    <aside className="w-64 h-screen flex flex-col border-r border-gray-800 bg-gray-950 shrink-0">
      {/* Logo */}
      <div className="h-16 flex items-center gap-2.5 px-6 border-b border-gray-800">
        <div className="w-8 h-8 gold-gradient rounded-lg flex items-center justify-center">
          <Scissors className="w-4 h-4 text-white" />
        </div>
        <span className="font-black text-lg tracking-tight">BarberPro</span>
      </div>

      {/* Nav */}
      <nav className="flex-1 p-4 space-y-1 overflow-y-auto">
        {filtered.map(({ href, icon: Icon, label }) => {
          const active = pathname === href || (href !== '/dashboard' && pathname.startsWith(href))
          return (
            <Link
              key={href}
              href={href}
              className={`flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group ${
                active
                  ? 'bg-gold-600/15 text-gold-400 border border-gold-600/20'
                  : 'text-gray-400 hover:text-gray-100 hover:bg-gray-800'
              }`}
            >
              <Icon className={`w-4.5 h-4.5 ${active ? 'text-gold-400' : 'text-gray-500 group-hover:text-gray-300'}`} style={{ width: '18px', height: '18px' }} />
              {label}
              {active && <ChevronRight className="w-3.5 h-3.5 ml-auto text-gold-400/60" />}
            </Link>
          )
        })}
      </nav>

      {/* Quick booking link */}
      <div className="px-4 pb-4">
        <Link
          href="/book"
          className="flex items-center gap-2 w-full px-3 py-2.5 bg-gold-600/10 hover:bg-gold-600/20
                     border border-gold-600/20 rounded-xl text-gold-400 text-sm font-medium transition-all"
        >
          <Calendar className="w-4 h-4" />
          Nueva reserva
        </Link>
      </div>

      {/* User */}
      <div className="border-t border-gray-800 p-4">
        <div className="flex items-center gap-3 mb-3">
          <Image
            src={profile.avatar_url || getAvatarUrl(profile.full_name)}
            alt={profile.full_name}
            width={36}
            height={36}
            className="rounded-full border border-gray-700"
          />
          <div className="flex-1 min-w-0">
            <div className="text-sm font-semibold truncate">{profile.full_name}</div>
            <div className="text-xs text-gold-500">{ROLE_LABEL[profile.role]}</div>
          </div>
        </div>
        <button
          onClick={handleLogout}
          className="flex items-center gap-2 w-full px-3 py-2 text-gray-500 hover:text-gray-300
                     hover:bg-gray-800 rounded-xl text-sm transition-all"
        >
          <LogOut className="w-4 h-4" />
          Cerrar sesión
        </button>
      </div>
    </aside>
  )
}
