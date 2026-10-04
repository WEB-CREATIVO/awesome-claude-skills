'use client'
import { useState } from 'react'
import { createClient } from '@/lib/supabase/client'
import { useRouter } from 'next/navigation'
import { Wrench, Plus, X, Edit2, Clock, DollarSign } from 'lucide-react'
import type { Service } from '@/lib/types'
import { formatPrice } from '@/lib/utils'

const CATEGORIES = ['Corte', 'Barba', 'Combo', 'Tratamiento', 'Otro']

const EMPTY: Partial<Service> = {
  name: '', description: '', duration_minutes: 30, price: 0, category: 'Corte', is_active: true,
}

export default function ServicesClient({ services }: { services: Service[] }) {
  const router = useRouter()
  const [showModal, setShowModal] = useState(false)
  const [form, setForm] = useState<Partial<Service>>(EMPTY)
  const [loading, setLoading] = useState(false)

  const openCreate = () => { setForm(EMPTY); setShowModal(true) }
  const openEdit = (s: Service) => { setForm({ ...s }); setShowModal(true) }

  const handleSave = async () => {
    if (!form.name) return
    setLoading(true)
    const supabase = createClient()

    if (form.id) {
      await supabase.from('services').update({
        name: form.name, description: form.description, duration_minutes: form.duration_minutes,
        price: form.price, category: form.category, is_active: form.is_active,
      }).eq('id', form.id)
    } else {
      await supabase.from('services').insert({
        name: form.name, description: form.description, duration_minutes: form.duration_minutes,
        price: form.price || 0, category: form.category,
      })
    }

    setShowModal(false)
    setLoading(false)
    router.refresh()
  }

  const toggleActive = async (s: Service) => {
    const supabase = createClient()
    await supabase.from('services').update({ is_active: !s.is_active }).eq('id', s.id)
    router.refresh()
  }

  const byCategory = CATEGORIES.reduce<Record<string, Service[]>>((acc, cat) => {
    acc[cat] = services.filter((s) => s.category === cat)
    return acc
  }, {})

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-black">Servicios</h1>
          <p className="text-gray-400 mt-1">{services.length} registrados</p>
        </div>
        <button onClick={openCreate} className="btn-primary inline-flex items-center gap-2">
          <Plus className="w-4 h-4" /> Nuevo servicio
        </button>
      </div>

      {CATEGORIES.map((cat) => {
        if (!byCategory[cat]?.length) return null
        return (
          <div key={cat}>
            <h2 className="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">{cat}</h2>
            <div className="space-y-2">
              {byCategory[cat].map((s) => (
                <div
                  key={s.id}
                  className={`card p-4 flex items-center gap-4 transition-all ${!s.is_active ? 'opacity-60' : 'hover:border-gray-700'}`}
                >
                  <div className="flex-1">
                    <div className="flex items-center gap-2">
                      <span className="font-semibold">{s.name}</span>
                      {!s.is_active && (
                        <span className="text-xs px-2 py-0.5 rounded-full bg-gray-800 text-gray-500">Inactivo</span>
                      )}
                    </div>
                    {s.description && <p className="text-sm text-gray-500 mt-0.5">{s.description}</p>}
                  </div>
                  <div className="flex items-center gap-6 text-sm">
                    <div className="flex items-center gap-1 text-gray-400">
                      <Clock className="w-4 h-4" />
                      {s.duration_minutes} min
                    </div>
                    <div className="text-gold-400 font-bold text-base">{formatPrice(s.price)}</div>
                  </div>
                  <div className="flex items-center gap-2">
                    <button
                      onClick={() => openEdit(s)}
                      className="p-2 text-gray-500 hover:text-gray-300 hover:bg-gray-800 rounded-xl transition-all"
                    >
                      <Edit2 className="w-4 h-4" />
                    </button>
                    <button
                      onClick={() => toggleActive(s)}
                      className={`px-3 py-1.5 rounded-xl text-xs font-medium transition-all ${
                        s.is_active
                          ? 'bg-red-900/30 text-red-400 hover:bg-red-900/50'
                          : 'bg-green-900/30 text-green-400 hover:bg-green-900/50'
                      }`}
                    >
                      {s.is_active ? 'Desactivar' : 'Activar'}
                    </button>
                  </div>
                </div>
              ))}
            </div>
          </div>
        )
      })}

      {/* Modal */}
      {showModal && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
          <div className="card w-full max-w-md animate-slide-up">
            <div className="flex items-center justify-between p-6 border-b border-gray-800">
              <h2 className="text-xl font-black">{form.id ? 'Editar servicio' : 'Nuevo servicio'}</h2>
              <button onClick={() => setShowModal(false)} className="p-2 hover:bg-gray-800 rounded-xl">
                <X className="w-5 h-5" />
              </button>
            </div>

            <div className="p-6 space-y-4">
              <div>
                <label className="block text-sm font-medium text-gray-300 mb-2">Nombre del servicio</label>
                <input
                  type="text"
                  value={form.name || ''}
                  onChange={(e) => setForm((f) => ({ ...f, name: e.target.value }))}
                  className="input-dark"
                  placeholder="Ej: Corte Clásico"
                />
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-300 mb-2">Descripción</label>
                <textarea
                  value={form.description || ''}
                  onChange={(e) => setForm((f) => ({ ...f, description: e.target.value }))}
                  rows={2}
                  className="input-dark resize-none"
                  placeholder="Descripción breve del servicio"
                />
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-medium text-gray-300 mb-2">Categoría</label>
                  <select
                    value={form.category || 'Corte'}
                    onChange={(e) => setForm((f) => ({ ...f, category: e.target.value }))}
                    className="input-dark"
                  >
                    {CATEGORIES.map((c) => <option key={c} value={c}>{c}</option>)}
                  </select>
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-300 mb-2">Duración (min)</label>
                  <input
                    type="number"
                    min={5}
                    step={5}
                    value={form.duration_minutes || 30}
                    onChange={(e) => setForm((f) => ({ ...f, duration_minutes: Number(e.target.value) }))}
                    className="input-dark"
                  />
                </div>
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-300 mb-2">Precio (centavos MXN)</label>
                <div className="relative">
                  <DollarSign className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" />
                  <input
                    type="number"
                    min={0}
                    step={100}
                    value={form.price || 0}
                    onChange={(e) => setForm((f) => ({ ...f, price: Number(e.target.value) }))}
                    className="input-dark pl-10"
                    placeholder="25000 = $250.00"
                  />
                </div>
                {(form.price || 0) > 0 && (
                  <p className="text-xs text-gold-400 mt-1">{formatPrice(form.price || 0)}</p>
                )}
              </div>
            </div>

            <div className="flex gap-3 p-6 border-t border-gray-800">
              <button onClick={() => setShowModal(false)} className="btn-secondary flex-1">Cancelar</button>
              <button onClick={handleSave} disabled={loading || !form.name} className="btn-primary flex-1">
                {loading ? <div className="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin mx-auto" /> : 'Guardar'}
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  )
}
