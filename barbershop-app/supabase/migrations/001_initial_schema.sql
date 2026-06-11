-- Enable UUID extension
create extension if not exists "uuid-ossp";

-- ─── PROFILES ────────────────────────────────────────────────────────────────
create table public.profiles (
  id          uuid references auth.users on delete cascade primary key,
  full_name   text not null,
  phone       text,
  role        text not null default 'client' check (role in ('admin','barber','client')),
  avatar_url  text,
  created_at  timestamptz default now() not null
);
alter table public.profiles enable row level security;

create policy "Users can view all profiles"
  on public.profiles for select using (true);

create policy "Users can update their own profile"
  on public.profiles for update using (auth.uid() = id);

-- Auto-create profile on sign-up
create or replace function public.handle_new_user()
returns trigger language plpgsql security definer set search_path = public as $$
begin
  insert into public.profiles (id, full_name, role)
  values (
    new.id,
    coalesce(new.raw_user_meta_data->>'full_name', 'Usuario'),
    coalesce(new.raw_user_meta_data->>'role', 'client')
  );
  return new;
end;
$$;

create trigger on_auth_user_created
  after insert on auth.users
  for each row execute procedure public.handle_new_user();

-- ─── SERVICES ─────────────────────────────────────────────────────────────────
create table public.services (
  id               uuid default uuid_generate_v4() primary key,
  name             text not null,
  description      text,
  duration_minutes integer not null default 30,
  price            integer not null,  -- stored in cents
  category         text not null default 'Corte',
  is_active        boolean default true,
  created_at       timestamptz default now() not null
);
alter table public.services enable row level security;

create policy "Anyone can view active services"
  on public.services for select using (is_active = true);

create policy "Admins manage services"
  on public.services for all
  using (exists (select 1 from public.profiles where id = auth.uid() and role = 'admin'));

-- ─── BARBERS ──────────────────────────────────────────────────────────────────
create table public.barbers (
  id          uuid default uuid_generate_v4() primary key,
  profile_id  uuid references public.profiles(id) on delete cascade not null unique,
  bio         text,
  specialties text[] default '{}',
  is_active   boolean default true,
  created_at  timestamptz default now() not null
);
alter table public.barbers enable row level security;

create policy "Anyone can view active barbers"
  on public.barbers for select using (is_active = true);

create policy "Admins manage barbers"
  on public.barbers for all
  using (exists (select 1 from public.profiles where id = auth.uid() and role = 'admin'));

create policy "Barbers can update their own record"
  on public.barbers for update
  using (profile_id = auth.uid());

-- ─── BARBER SERVICES ──────────────────────────────────────────────────────────
create table public.barber_services (
  barber_id  uuid references public.barbers(id) on delete cascade,
  service_id uuid references public.services(id) on delete cascade,
  primary key (barber_id, service_id)
);
alter table public.barber_services enable row level security;

create policy "Anyone can view barber services"
  on public.barber_services for select using (true);

create policy "Admins manage barber services"
  on public.barber_services for all
  using (exists (select 1 from public.profiles where id = auth.uid() and role = 'admin'));

-- ─── WORKING HOURS ────────────────────────────────────────────────────────────
create table public.working_hours (
  id          uuid default uuid_generate_v4() primary key,
  barber_id   uuid references public.barbers(id) on delete cascade not null,
  day_of_week integer not null check (day_of_week between 0 and 6),
  start_time  time not null,
  end_time    time not null,
  is_active   boolean default true,
  unique(barber_id, day_of_week)
);
alter table public.working_hours enable row level security;

create policy "Anyone can view working hours"
  on public.working_hours for select using (true);

create policy "Admins manage working hours"
  on public.working_hours for all
  using (exists (select 1 from public.profiles where id = auth.uid() and role = 'admin'));

create policy "Barbers manage their own hours"
  on public.working_hours for all
  using (exists (select 1 from public.barbers where id = barber_id and profile_id = auth.uid()));

-- ─── APPOINTMENTS ─────────────────────────────────────────────────────────────
create table public.appointments (
  id               uuid default uuid_generate_v4() primary key,
  client_id        uuid references public.profiles(id) on delete cascade not null,
  barber_id        uuid references public.barbers(id) on delete cascade not null,
  service_id       uuid references public.services(id) on delete cascade not null,
  appointment_date date not null,
  start_time       time not null,
  end_time         time not null,
  status           text not null default 'pending'
                     check (status in ('pending','confirmed','cancelled','completed')),
  notes            text,
  total_price      integer not null,
  created_at       timestamptz default now() not null
);
alter table public.appointments enable row level security;

create policy "Clients see their own appointments"
  on public.appointments for select
  using (client_id = auth.uid());

create policy "Barbers see their own appointments"
  on public.appointments for select
  using (exists (select 1 from public.barbers where id = barber_id and profile_id = auth.uid()));

create policy "Admins see all appointments"
  on public.appointments for select
  using (exists (select 1 from public.profiles where id = auth.uid() and role = 'admin'));

create policy "Authenticated users can create appointments"
  on public.appointments for insert
  with check (auth.uid() = client_id);

create policy "Clients can cancel their own appointments"
  on public.appointments for update
  using (client_id = auth.uid() and status = 'pending');

create policy "Barbers can update their appointments"
  on public.appointments for update
  using (exists (select 1 from public.barbers where id = barber_id and profile_id = auth.uid()));

create policy "Admins can update all appointments"
  on public.appointments for update
  using (exists (select 1 from public.profiles where id = auth.uid() and role = 'admin'));

-- ─── SEED DATA ────────────────────────────────────────────────────────────────
insert into public.services (name, description, duration_minutes, price, category) values
  ('Corte Clásico',      'Corte tradicional con tijeras y máquina',     30,  25000, 'Corte'),
  ('Corte + Barba',      'Servicio completo de corte y arreglo de barba', 50, 40000, 'Combo'),
  ('Arreglo de Barba',   'Perfilado y arreglo profesional de barba',     25,  18000, 'Barba'),
  ('Corte Degradado',    'Fade o degradado con detallado perfecto',       40,  30000, 'Corte'),
  ('Tratamiento Capilar','Tratamiento nutritivo y masaje capilar',        45,  35000, 'Tratamiento'),
  ('Afeitado Clásico',   'Afeitado con navaja y toalla caliente',         35,  22000, 'Barba');
