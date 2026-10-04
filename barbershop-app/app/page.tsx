import Link from 'next/link'
import { Scissors, Calendar, Clock, Star, Users, CheckCircle, ArrowRight, Zap } from 'lucide-react'

const features = [
  { icon: Calendar, title: 'Reserva Online 24/7', desc: 'Agenda tu cita en cualquier momento, desde cualquier lugar' },
  { icon: Clock, title: 'Horarios Flexibles', desc: 'Elige el horario que mejor se adapte a tu agenda' },
  { icon: Star, title: 'Barberos Expertos', desc: 'Equipo de profesionales con años de experiencia' },
  { icon: Zap, title: 'Confirmación Inmediata', desc: 'Recibe confirmación instantánea de tu cita' },
]

const services = [
  { name: 'Corte Clásico', price: '$250', time: '30 min' },
  { name: 'Corte + Barba', price: '$400', time: '50 min' },
  { name: 'Arreglo de Barba', price: '$180', time: '25 min' },
  { name: 'Corte Degradado', price: '$300', time: '40 min' },
  { name: 'Tratamiento Capilar', price: '$350', time: '45 min' },
  { name: 'Afeitado Clásico', price: '$220', time: '35 min' },
]

export default function HomePage() {
  return (
    <div className="min-h-screen bg-[rgb(15,15,20)]">
      {/* Nav */}
      <nav className="fixed top-0 w-full z-50 border-b border-white/5 bg-[rgb(15,15,20)]/80 backdrop-blur-xl">
        <div className="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
          <div className="flex items-center gap-2">
            <div className="w-8 h-8 gold-gradient rounded-lg flex items-center justify-center">
              <Scissors className="w-4 h-4 text-white" />
            </div>
            <span className="font-bold text-xl tracking-tight">BarberPro</span>
          </div>
          <div className="flex items-center gap-3">
            <Link href="/login" className="text-gray-400 hover:text-white transition-colors px-4 py-2 text-sm">
              Iniciar sesión
            </Link>
            <Link href="/book" className="btn-primary text-sm py-2">
              Reservar cita
            </Link>
          </div>
        </div>
      </nav>

      {/* Hero */}
      <section className="pt-32 pb-24 px-6 relative overflow-hidden">
        <div className="absolute inset-0 bg-gradient-to-b from-gold-900/10 via-transparent to-transparent pointer-events-none" />
        <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-gold-600/5 rounded-full blur-3xl pointer-events-none" />

        <div className="max-w-4xl mx-auto text-center relative">
          <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gold-500/10 border border-gold-500/20 text-gold-400 text-sm mb-8">
            <CheckCircle className="w-4 h-4" />
            Sistema profesional de gestión de citas
          </div>

          <h1 className="text-6xl font-black tracking-tight mb-6 leading-tight">
            Tu estilo,{' '}
            <span className="text-gold-gradient">a un clic</span>{' '}
            de distancia
          </h1>

          <p className="text-xl text-gray-400 mb-10 max-w-2xl mx-auto leading-relaxed">
            Reserva tu cita en nuestra peluquería de forma rápida y sencilla.
            Elige tu barbero, servicio y horario favorito.
          </p>

          <div className="flex items-center justify-center gap-4 flex-wrap">
            <Link href="/book" className="btn-primary inline-flex items-center gap-2 text-base py-4 px-8">
              Reservar ahora
              <ArrowRight className="w-5 h-5" />
            </Link>
            <Link href="/login" className="btn-secondary inline-flex items-center gap-2 text-base py-4 px-8">
              Acceder al panel
            </Link>
          </div>

          {/* Stats */}
          <div className="mt-20 grid grid-cols-3 gap-8 max-w-lg mx-auto">
            {[
              { value: '2,500+', label: 'Clientes felices' },
              { value: '8', label: 'Barberos expertos' },
              { value: '4.9★', label: 'Valoración media' },
            ].map((s) => (
              <div key={s.label} className="text-center">
                <div className="text-3xl font-black text-gold-400">{s.value}</div>
                <div className="text-sm text-gray-500 mt-1">{s.label}</div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Features */}
      <section className="py-24 px-6">
        <div className="max-w-6xl mx-auto">
          <div className="text-center mb-16">
            <h2 className="text-4xl font-black mb-4">Por qué elegirnos</h2>
            <p className="text-gray-400 text-lg">Una experiencia diseñada para tu comodidad</p>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {features.map(({ icon: Icon, title, desc }) => (
              <div key={title} className="card p-6 hover:border-gold-800/50 transition-all group">
                <div className="w-12 h-12 gold-gradient rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                  <Icon className="w-6 h-6 text-white" />
                </div>
                <h3 className="font-bold text-lg mb-2">{title}</h3>
                <p className="text-gray-400 text-sm leading-relaxed">{desc}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Services */}
      <section className="py-24 px-6 bg-gray-950/50">
        <div className="max-w-6xl mx-auto">
          <div className="text-center mb-16">
            <h2 className="text-4xl font-black mb-4">Nuestros servicios</h2>
            <p className="text-gray-400 text-lg">Precios claros, calidad garantizada</p>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            {services.map(({ name, price, time }) => (
              <div key={name} className="card p-5 flex items-center justify-between hover:border-gold-800/40 transition-all">
                <div>
                  <div className="font-semibold">{name}</div>
                  <div className="text-sm text-gray-500 mt-0.5 flex items-center gap-1">
                    <Clock className="w-3.5 h-3.5" /> {time}
                  </div>
                </div>
                <div className="text-gold-400 font-black text-xl">{price}</div>
              </div>
            ))}
          </div>
          <div className="text-center mt-10">
            <Link href="/book" className="btn-primary inline-flex items-center gap-2">
              Reservar ahora <ArrowRight className="w-4 h-4" />
            </Link>
          </div>
        </div>
      </section>

      {/* CTA */}
      <section className="py-24 px-6">
        <div className="max-w-3xl mx-auto text-center card p-12 relative overflow-hidden">
          <div className="absolute inset-0 bg-gradient-to-br from-gold-900/20 to-transparent pointer-events-none rounded-2xl" />
          <Users className="w-12 h-12 text-gold-400 mx-auto mb-6" />
          <h2 className="text-4xl font-black mb-4">¿Eres barbero o dueño?</h2>
          <p className="text-gray-400 text-lg mb-8">
            Gestiona todas tus citas, clientes y servicios desde un panel centralizado
          </p>
          <Link href="/register" className="btn-primary inline-flex items-center gap-2 text-base py-4 px-8">
            Crear cuenta gratis <ArrowRight className="w-5 h-5" />
          </Link>
        </div>
      </section>

      {/* Footer */}
      <footer className="border-t border-gray-800 py-8 px-6">
        <div className="max-w-6xl mx-auto flex items-center justify-between">
          <div className="flex items-center gap-2">
            <div className="w-7 h-7 gold-gradient rounded-lg flex items-center justify-center">
              <Scissors className="w-3.5 h-3.5 text-white" />
            </div>
            <span className="font-bold text-gray-400">BarberPro</span>
          </div>
          <p className="text-sm text-gray-600">© 2026 BarberPro. Todos los derechos reservados.</p>
        </div>
      </footer>
    </div>
  )
}
