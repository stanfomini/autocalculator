<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Scheduler</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100 p-4" x-data="appointmentApp()">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-md shadow">
        <h1 class="text-2xl font-bold mb-4">Appointment Scheduler</h1>

        <div class="flex gap-4 mb-4">
            <button class="px-4 py-2 rounded bg-blue-500 text-white"
                    :class="{ 'bg-blue-700': tab === 'form' }"
                    @click="tab = 'form'">
                Schedule Appointment
            </button>
            <button class="px-4 py-2 rounded bg-blue-500 text-white"
                    :class="{ 'bg-blue-700': tab === 'list' }"
                    @click="tab = 'list'">
                Appointment List
            </button>
        </div>

        <!-- TAB 1: Form -->
        <div x-show="tab === 'form'" class="space-y-4">
            <form @submit.prevent="createAppointment" class="space-y-4">
                <div>
                    <label class="block font-semibold">First Name</label>
                    <input type="text" x-model="form.first_name" class="border p-2 w-full" required>
                </div>
                <div>
                    <label class="block font-semibold">Last Name</label>
                    <input type="text" x-model="form.last_name" class="border p-2 w-full" required>
                </div>
                <div>
                    <label class="block font-semibold">Phone</label>
                    <input type="text" x-model="form.phone" class="border p-2 w-full" required>
                </div>
                <div>
                    <label class="block font-semibold">Appointment Date &amp; Time</label>
                    <input type="datetime-local" x-model="form.appointment_datetime" class="border p-2 w-full" required>
                </div>
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded">
                    Create Appointment
                </button>
            </form>

            <template x-if="message">
                <div class="text-green-600 font-semibold" x-text="message"></div>
            </template>
        </div>

        <!-- TAB 2: List -->
        <div x-show="tab === 'list'">
            <h2 class="text-xl font-bold mb-2">Current Appointments</h2>
            <p class="text-sm text-gray-500 mb-4">(Real-time updates via SSE)</p>

            <ul class="space-y-2">
                <template x-for="appointment in appointments" :key="appointment.id">
                    <li class="bg-gray-50 p-3 rounded flex items-center justify-between">
                        <div>
                            <span class="font-semibold" x-text="appointment.first_name + ' ' + appointment.last_name"></span><br>
                            <span class="text-sm text-gray-500" x-text="appointment.phone"></span><br>
                            <span class="text-sm font-medium" x-text="formatDate(appointment.appointment_datetime)"></span>
                        </div>
                        <div>
                            <template x-if="appointment.is_new">
                                <span class="inline-block w-3 h-3 rounded-full bg-green-500"></span>
                            </template>
                        </div>
                    </li>
                </template>
            </ul>
        </div>
    </div>

    <script>
    function appointmentApp() {
        return {
            tab: 'form',
            message: '',
            appointments: [],
            form: {
                first_name: '',
                last_name: '',
                phone: '',
                appointment_datetime: '',
            },
            async createAppointment() {
                // Clear message
                this.message = '';

                const resp = await fetch('/schedule', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify(this.form),
                });
                const data = await resp.json();
                if (data.status === 'success') {
                    this.message = 'Appointment created successfully!';
                    // Reset form
                    this.form = {
                        first_name: '',
                        last_name: '',
                        phone: '',
                        appointment_datetime: '',
                    };
                }
            },
            formatDate(datetimeStr) {
                const d = new Date(datetimeStr);
                return d.toLocaleString();
            },
            init() {
                // Initialize SSE to receive appointment updates in real-time
                let source = new EventSource('/appointments/sse');
                source.onmessage = (event) => {
                    // Parse incoming JSON
                    const parsed = JSON.parse(event.data);
                    this.appointments = parsed;
                };
                source.onerror = (err) => {
                    console.error('SSE Error:', err);
                };
            },
        };
    }
    </script>

    
    <script>
    document.addEventListener('alpine:init', () => {
      // Nothing special here, just ensuring Alpine is loaded
    });
    </script>
</body>
</html>
