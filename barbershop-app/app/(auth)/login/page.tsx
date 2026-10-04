'use client'
import { useState } from 'react'
import Link from 'next/link'
import { useRouter } from 'next/navigation'
import { createClient } from '@/lib/supabase/client'
import { Eye, EyeOff, LogIn } from 'lucide-react'

export default function LoginPage() {
  const router = useRouter()
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [showPass, setShowPass] = useState(false)
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState('')

  const handleLogin = async (e: React.FormEvent) => {
    e.preventDefault()
    setLoading(true)
    setError('')

    const supabase = createClient()
    const { error } = await supabase.auth.signInWithPassword({ email, password })

    if (error) {
      setError('Credenciales incorrectas. Verifica tu email y contraseña.')
      setLoading(false)
      return
    }

    router.push('/dashboard')
    router.refresh()
  }

  return (
    <div className="card p-8">
      <div className="mb-8">
        <h1 className="text-2xl font-black">Bienvenido de vuelta</h1>
        <p className="text-gray-400 mt-1">Inicia sesión en tu cuenta</p>
      </div>

      <form onSubmit={handleLogin} className="space-y-4">
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
          <label className="block text-sm font-medium text-gray-300 mb-2">Contraseña</label>
          <div className="relative">
            <input
              type={showPass ? 'text' : 'password'}
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              className="input-dark pr-12"
              placeholder="••••••••"
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
              <LogIn className="w-4 h-4" />
              Iniciar sesión
            </>
          )}
        </button>
      </form>

      <div className="mt-6 pt-6 border-t border-gray-800 text-center">
        <p className="text-gray-400 text-sm">
          ¿No tienes cuenta?{' '}
          <Link href="/register" className="text-gold-400 hover:text-gold-300 font-medium">
            Crear cuenta gratis
          </Link>
        </p>
      </div>

      {/* Demo accounts */}
      <div className="mt-4 p-4 bg-gray-950 rounded-xl border border-gray-800">
        <p className="text-xs text-gray-500 font-medium mb-2">Cuentas de prueba:</p>
        <div className="space-y-1 text-xs text-gray-600">
          <div>Admin: <span className="text-gray-400">admin@barberpro.com / admin123</span></div>
          <div>Barbero: <span className="text-gray-400">carlos@barberpro.com / barber123</span></div>
          <div>Cliente: <span className="text-gray-400">cliente@barberpro.com / client123</span></div>
        </div>
      </div>
    </div>
  )
}
