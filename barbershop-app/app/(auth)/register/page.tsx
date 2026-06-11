'use client'
import { useState } from 'react'
import Link from 'next/link'
import { useRouter } from 'next/navigation'
import { createClient } from '@/lib/supabase/client'
import { Eye, EyeOff, UserPlus, User, Scissors } from 'lucide-react'
import type { UserRole } from '@/lib/types'

const roles: { value: UserRole; label: string; desc: string; icon: React.ElementType }[] = [
  { value: 'client',  label: 'Cliente',  desc: 'Reservo citas',         icon: User },
  { value: 'barber',  label: 'Barbero',  desc: 'Gestiono mis horarios', icon: Scissors },
]

export default function RegisterPage() {
  const router = useRouter()
  const [fullName, setFullName] = useState('')
  const [email, setEmail] = useState('')
  const [phone, setPhone] = useState('')
  const [password, setPassword] = useState('')
  const [showPass, setShowPass] = useState(false)
  const [role, setRole] = useState<UserRole>('client')
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState('')

  const handleRegister = async (e: React.FormEvent) => {
    e.preventDefault()
    if (password.length < 6) {
      setError('La contraseña debe tener al menos 6 caracteres.')
      return
    }
    setLoading(true)
    setError('')

    const supabase = createClient()
    const { error } = await supabase.auth.signUp({
      email,
      password,
      options: {
        data: { full_name: fullName, role },
        emailRedirectTo: `${window.location.origin}/dashboard`,
      },
    })

    if (error) {
      setError(error.message)
      setLoading(false)
      return
    }

    router.push('/dashboard')
    router.refresh()
  }

  return (
    <div className="card p-8">
      <div className="mb-8">
        <h1 className="text-2xl font-black">Crea tu cuenta</h1>
        <p className="text-gray-400 mt-1">Únete a BarberPro gratis</p>
      </div>

      <form onSubmit={handleRegister} className="space-y-4">
        {/* Role selector */}
        <div>
          <label className="block text-sm font-medium text-gray-300 mb-2">Soy…</label>
          <div className="grid grid-cols-2 gap-3">
            {roles.map(({ value, label, desc, icon: Icon }) => (
              <button
                key={value}
                type="button"
                onClick={() => setRole(value)}
                className={`p-4 rounded-xl border text-left transition-all ${
                  role === value
                    ? 'border-gold-500 bg-gold-500/10'
                    : 'border-gray-700 bg-gray-900 hover:border-gray-600'
                }`}
              >
                <Icon className={`w-5 h-5 mb-2 ${role === value ? 'text-gold-400' : 'text-gray-500'}`} />
                <div className={`font-semibold text-sm ${role === value ? 'text-gold-400' : 'text-gray-300'}`}>
                  {label}
                </div>
                <div className="text-xs text-gray-500 mt-0.5">{desc}</div>
              </button>
            ))}
          </div>
        </div>

        <div>
          <label className="block text-sm font-medium text-gray-300 mb-2">Nombre completo</label>
          <input
            type="text"
            value={fullName}
            onChange={(e) => setFullName(e.target.value)}
            className="input-dark"
            placeholder="Juan García"
            required
          />
        </div>

        <div>
          <label className="block text-sm font-medium text-gray-300 mb-2">Email</label>
          <input
            type="email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            className="input-dark"
            placeholder="tu@email.com"
            required
          />
        </div>

        <div>
          <label className="block text-sm font-medium text-gray-300 mb-2">Teléfono <span className="text-gray-500">(opcional)</span></label>
          <input
            type="tel"
            value={phone}
            onChange={(e) => setPhone(e.target.value)}
            className="input-dark"
            placeholder="+52 55 0000 0000"
          />
        </div>

        <div>
          <label className="block text-sm font-medium text-gray-300 mb-2">Contraseña</label>
          <div className="relative">
            <input
              type={showPass ? 'text' : 'password'}
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              className="input-dark pr-12"
              placeholder="Mínimo 6 caracteres"
              required
            />
            <button
              type="button"
              onClick={() => setShowPass(!showPass)}
              className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-300"
            >
              {showPass ? <EyeOff className="w-5 h-5" /> : <Eye className="w-5 h-5" />}
            </button>
          </div>
        </div>

        {error && (
          <div className="p-3 bg-red-900/30 border border-red-800 rounded-xl text-red-400 text-sm">
            {error}
          </div>
        )}

        <button type="submit" disabled={loading} className="btn-primary w-full flex items-center justify-center gap-2">
          {loading ? (
            <div className="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin" />
          ) : (
            <>
              <UserPlus className="w-4 h-4" />
              Crear cuenta
            </>
          )}
        </button>
      </form>

      <div className="mt-6 pt-6 border-t border-gray-800 text-center">
        <p className="text-gray-400 text-sm">
          ¿Ya tienes cuenta?{' '}
          <Link href="/login" className="text-gold-400 hover:text-gold-300 font-medium">
            Iniciar sesión
          </Link>
        </p>
      </div>
    </div>
  )
}
