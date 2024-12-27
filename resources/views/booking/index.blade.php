<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100 p-4" x-data="bookingApp()">

    <div class="max-w-3xl mx-auto bg-white p-6 rounded-md shadow">
        <h1 class="text-2xl font-bold mb-4">Appointment Booking (SPA)</h1>

        <div class="flex gap-4 mb-4">
            <button class="px-4 py-2 rounded bg-blue-500 text-white"
                :class="{ 'bg-blue-700': tab === 'form' }"
                @click="tab = 'form'">
                Create Appointment
            </button>
            <button class="px-4 py-2 rounded bg-blue-500 text-white"
                :class="{ 'bg-blue-700': tab === 'list' }"
                @click="tab = 'list'">
                View Appointments
            </button>
        </div>

        <!-- Tab 1: Form -->
        <div x-show="tab === 'form'" class="space-y-4">
            <form @submit.prevent="createAppointment" class="space-y-4">
                <div>
                    <label class="block font-semibold">First Name</label>
                    <input type="text" x-model="form.first_name"
                           class="border p-2 w-full" required>
                </div>
                <div>
                    <label class="block font-semibold">Last Name</label>
                    <input type="text" x-model="form.last_name"
                           class="border p-2 w-full" required>
                </div>
                <div>
                    <label class="block font-semibold">Phone</label>
                    <input type="text" x-model="form.phone"
                           class="border p-2 w-full" required>
                </div>
                <div>
                    <label class="block font-semibold">Appointment Date & Time</label>
                    <input type="datetime-local" x-model="form.appointment_datetime"
                           class="border p-2 w-full" required>
                </div>
                <button type="submit"
                        class="px-4 py-2 bg-green-500 text-white rounded">
                    Book
                </button>
            </form>
            <template x-if="message">
                <div class="text-green-600 font-semibold" x-text="message"></div>
            </template>
        </div>

        <!-- Tab 2: List -->
        <div x-show="tab === 'list'">
            <h2 class="text-xl font-bold mb-2">All Appointments</h2>
            <p class="text-sm text-gray-500 mb-4">
                Real-time updates via SSE (refresh every 3s).
            </p>
            <ul class="space-y-2">
                <template x-for="appt in appointments" :key="appt.id">
                    <li class="bg-gray-50 p-3 rounded flex items-center justify-between">
                        <div>
                            <span class="font-semibold"
                                  x-text="appt.first_name + ' ' + appt.last_name"></span>
                            <br>
                            <span class="text-sm text-gray-500"
                                  x-text="appt.phone"></span>
                            <br>
                            <span class="text-sm font-medium"
                                  x-text="formatDate(appt.appointment_datetime)"></span>
                        </div>
                        <div class="flex items-center gap-3">
                            <!-- Show green circle if newly created in last 10 min -->
                            <template x-if="appt.is_new">
                                <span class="inline-block w-3 h-3 rounded-full bg-green-500"></span>
                            </template>
                            <!-- Buttons for editing/deleting -->
                            <button class="text-sm text-blue-600 underline"
                                @click="editAppointment(appt)">
                                Edit
                            </button>
                            <button class="text-sm text-red-600 underline"
                                @click="deleteAppointment(appt.id)">
                                Delete
                            </button>
                        </div>
                    </li>
                </template>
            </ul>

            <!-- Optional edit modal -->
            <template x-if="editing">
                <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white p-6 rounded w-96 space-y-4">
                        <h3 class="text-lg font-bold">Edit Appointment</h3>
                        <form @submit.prevent="updateAppointment" class="space-y-3">
                            <div>
                                <label class="block font-semibold">First Name</label>
                                <input type="text" x-model="editForm.first_name"
                                       class="border p-2 w-full" required>
                            </div>
                            <div>
                                <label class="block font-semibold">Last Name</label>
                                <input type="text" x-model="editForm.last_name"
                                       class="border p-2 w-full" required>
                            </div>
                            <div>
                                <label class="block font-semibold">Phone</label>
                                <input type="text" x-model="editForm.phone"
                                       class="border p-2 w-full" required>
                            </div>
                            <div>
                                <label class="block font-semibold">Date & Time</label>
                                <input type="datetime-local"
                                       x-model="editForm.appointment_datetime"
                                       class="border p-2 w-full" required>
                            </div>
                            <div class="flex justify-end gap-2">
                                <button type="button" class="px-4 py-2 bg-gray-400 text-white rounded"
                                        @click="closeEdit">
                                    Cancel
                                </button>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <script>
    function bookingApp() {
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
            editing: false,
            editId: null,
            editForm: {
                first_name: '',
                last_name: '',
                phone: '',
                appointment_datetime: '',
            },
            async createAppointment() {
                this.message = '';
                const resp = await fetch('/booking', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify(this.form),
                });
                const data = await resp.json();
                if (data.status === 'success') {
                    this.message = 'Appointment booked successfully!';
                    this.form = {
                        first_name: '',
                        last_name: '',
                        phone: '',
                        appointment_datetime: '',
                    };
                    this.tab = 'list';
                }
            },
            editAppointment(appt) {
                this.editing = true;
                this.editId = appt.id;
                this.editForm = {
                    first_name: appt.first_name,
                    last_name: appt.last_name,
                    phone: appt.phone,
                    appointment_datetime: appt.appointment_datetime?.slice(0,16),
                };
            },
            closeEdit() {
                this.editing = false;
                this.editId = null;
            },
            async updateAppointment() {
                const resp = await fetch(`/booking/${this.editId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify(this.editForm),
                });
                const data = await resp.json();
                if (data.status === 'success') {
                    this.editing = false;
                    this.editId = null;
                }
            },
            async deleteAppointment(id) {
                if (!confirm('Are you sure you want to delete this appointment?')) {
                    return;
                }
                const resp = await fetch(`/booking/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });
                const json = await resp.json();
                if (json.status === 'deleted') {
                    // SSE will drop it from the list on next refresh
                }
            },
            formatDate(dtStr) {
                const d = new Date(dtStr);
                return d.toLocaleString();
            },
            init() {
                // SSE for real-time updates
                const source = new EventSource('/booking/sse');
                source.onmessage = (evt) => {
                    try {
                        this.appointments = JSON.parse(evt.data);
                    } catch(e) {
                        console.error('SSE parse error:', e);
                    }
                };
                source.onerror = (err) => {
                    console.error('SSE error:', err);
                };
            },
        };
    }
    </script>
</body>
</html>