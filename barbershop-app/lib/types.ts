export type UserRole = 'admin' | 'barber' | 'client'
export type AppointmentStatus = 'pending' | 'confirmed' | 'cancelled' | 'completed'
export type DayOfWeek = 0 | 1 | 2 | 3 | 4 | 5 | 6

export interface Profile {
  id: string
  full_name: string
  phone: string | null
  role: UserRole
  avatar_url: string | null
  created_at: string
}

export interface Barber {
  id: string
  profile_id: string
  bio: string | null
  specialties: string[]
  is_active: boolean
  created_at: string
  profile?: Profile
}

export interface Service {
  id: string
  name: string
  description: string | null
  duration_minutes: number
  price: number
  category: string
  is_active: boolean
  created_at: string
}

export interface WorkingHours {
  id: string
  barber_id: string
  day_of_week: DayOfWeek
  start_time: string
  end_time: string
  is_active: boolean
}

export interface Appointment {
  id: string
  client_id: string
  barber_id: string
  service_id: string
  appointment_date: string
  start_time: string
  end_time: string
  status: AppointmentStatus
  notes: string | null
  total_price: number
  created_at: string
  client?: Profile
  barber?: Barber & { profile: Profile }
  service?: Service
}

export interface DashboardStats {
  total_appointments: number
  pending_appointments: number
  confirmed_appointments: number
  completed_appointments: number
  total_revenue: number
  total_clients: number
  total_barbers: number
}
