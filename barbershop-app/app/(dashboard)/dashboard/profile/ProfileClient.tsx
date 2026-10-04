'use client'
import { useState } from 'react'
import { createClient } from '@/lib/supabase/client'
import { useRouter } from 'next/navigation'
import { UserCircle, Save, Phone, Mail, FileText } from 'lucide-react'
import type { Profile, Barber } from '@/lib/types'
import { getAvatarUrl } from '@/lib/utils'
import Image from 'next/image'

const ROLE_LABEL: Record<string, string> = {
  admin: 'Administrador', barber: 'Barbero', client: 'Cliente',
}

export default function ProfileClient({
  profile, barberData, email,
}: {
  profile: Profile
  barberData: Barber | null
  email: string
}) {
  const router = useRouter()
  const [fullName, setFullName] = useState(profile.full_name)
  const [phone, setPhone] = useState(profile.phone || '')
  const [bio, setBio] = useState(barberData?.bio || '')
  const [loading, setLoading] = useState(false)
  const [saved, setSaved] = useState(false)

  const handleSave = async () => {
    setLoading(true)
    const supabase = createClient()

    await supabase.from('profiles').update({ full_name: fullName, phone }).eq('id', profile.id)

    if (barberData && profile.role === 'barber') {
      await supabase.from('barbers').update({ bio }).eq('id', barberData.id)
    }

    setSaved(true)
    setTimeout(() => setSaved(false), 3000)
    setLoading(false)
    router.refresh()
  }

  return (
    <div className="space-y-6 max-w-2xl">
      <div>
        <h1 className="text-3xl font-black">Mi perfil</h1>
        <p className="text-gray-400 mt-1">Gestiona tu información personal</p>
      </div>

      {/* Avatar */}
      <div className="card p-6 flex items-center gap-6">
        <Image
          src={profile.avatar_url || getAvatarUrl(fullName)}
          alt={fullName}
          width={80}
          height={80}
          className="rounded-2xl border-2 border-gray-700"
        />
        <div>
          <div className="font-black text-xl">{fullName}</div>
          <div className="text-gold-400 text-sm font-medium">{ROLE_LABEL[profile.role]}</div>
          <div className="text-gray-500 text-sm mt-0.5">{email}</div>
        </div>
      </div>

      {/* Form */}
      <div className="card p-6 space-y-5">
        <h2 className="font-bold text-lg">Información personal</h2>

        <div>
          <label className="block text-sm font-medium text-gray-300 mb-2">
            <UserCircle className="w-4 h-4 inline mr-1.5" />
            Nombre completo
          </label>
          <input
            type="text"
            value={fullName}
            onChange={(e) => setFullName(e.target.value)}
            className="input-dark"
          />
        </div>

        <div>
          <label className="block text-sm font-medium text-gray-300 mb-2">
            <Mail className="w-4 h-4 inline mr-1.5" />
            Email
          </label>
          <input type="email" value={email} disabled className="input-dark opacity-50 cursor-not-allowed" />
          <p className="text-xs text-gray-600 mt-1">El email no puede modificarse</p>
        </div>

        <div>
          <label className="block text-sm font-medium text-gray-300 mb-2">
            <Phone className="w-4 h-4 inline mr-1.5" />
            Teléfono
          </label>
          <input
            type="tel"
            value={phone}
            onChange={(e) => setPhone(e.target.value)}
            className="input-dark"
            placeholder="+52 55 0000 0000"
          />
        </div>

        {profile.role === 'barber' && (
          <div>
            <label className="block text-sm font-medium text-gray-300 mb-2">
              <FileText className="w-4 h-4 inline mr-1.5" />
              Biografía profesional
            </label>
            <textarea
              value={bio}
              onChange={(e) => setBio(e.target.value)}
              rows={4}
              className="input-dark resize-none"
              placeholder="Cuéntanos sobre tu experiencia y especialidades..."
            />
          </div>
        )}

        <div className="flex items-center gap-3 pt-2">
          <button onClick={handleSave} disabled={loading} className="btn-primary inline-flex items-center gap-2">
            {loading ? (
              <div className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin" />
            ) : (
              <Save className="w-4 h-4" />
            )}
            Guardar cambios
          </button>
          {saved && (
            <span className="text-green-400 text-sm animate-fade-in">
              ✓ Cambios guardados
            </span>
          )}
        </div>
      </div>
    </div>
  )
}
