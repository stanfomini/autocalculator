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
                    <label class="block font-semibold">Appointment Date &amp; Time</label>
                    <input type="datetime-local" x-model="form.appointment_datetime"
                           class="border p-2 w-full" required>
                </div>
                <button type="submit"
                        class="px-4 py-2 bg-green-500 text-white rounded">
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
            <p class="text-sm text-gray-500 mb-4">(Real-time updates via SSE; refreshes every 3s in this demo)</p>

            <ul class="space-y-2">
                <template x-for="appointment in appointments" :key="appointment.id">
                    <li class="bg-gray-50 p-3 rounded flex items-center justify-between">
                        <div>
                            <span class="font-semibold"
                                  x-text="appointment.first_name + ' ' + appointment.last_name"></span>
                            <br>
                            <span class="text-sm text-gray-500"
                                  x-text="appointment.phone"></span>
                            <br>
                            <span class="text-sm font-medium"
                                  x-text="formatDate(appointment.appointment_datetime)"></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <!-- If new, show green circle -->
                            <template x-if="appointment.is_new">
                                <span class="inline-block w-3 h-3 rounded-full bg-green-500"></span>
                            </template>
                            <!-- Buttons for edit/delete -->
                            <button class="text-sm text-blue-700 underline"
                                    @click="editAppointment(appointment)">
                                Edit
                            </button>
                            <button class="text-sm text-red-700 underline"
                                    @click="deleteAppointment(appointment.id)">
                                Delete
                            </button>
                        </div>
                    </li>
                </template>
            </ul>

            <!-- Optional Edit Modal -->
            <template x-if="editing">
                <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white p-6 rounded space-y-4 w-96">
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
                                <label class="block font-semibold">Appointment Date &amp; Time</label>
                                <input type="datetime-local" x-model="editForm.appointment_datetime"
                                       class="border p-2 w-full" required>
                            </div>
                            <div class="flex justify-end gap-2">
                                <button type="button"
                                        class="px-4 py-2 bg-gray-400 text-white rounded"
                                        @click="closeEdit">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white rounded">
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
    function appointmentApp() {
        return {
            tab: 'form',
            message: '',
            // List of appointments from SSE
            appointments: [],
            // Create form data
            form: {
                first_name: '',
                last_name: '',
                phone: '',
                appointment_datetime: '',
            },
            // Editing mode
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
                    // Switch to list tab
                    this.tab = 'list';
                }
            },
            editAppointment(appointment) {
                this.editing = true;
                this.editId = appointment.id;
                this.editForm = {
                    first_name: appointment.first_name,
                    last_name: appointment.last_name,
                    phone: appointment.phone,
                    appointment_datetime: appointment.appointment_datetime?.slice(0,16), 
                };
            },
            async updateAppointment() {
                const resp = await fetch('/schedule/' + this.editId, {
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
                const resp = await fetch('/schedule/' + id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });
                const data = await resp.json();
                if (data.status === 'deleted') {
                    // It will be removed on next SSE refresh
                }
            },
            closeEdit() {
                this.editing = false;
                this.editId = null;
            },
            formatDate(dtStr) {
                let d = new Date(dtStr);
                return d.toLocaleString();
            },
            init() {
                // SSE connection for real-time listing
                const source = new EventSource('/appointments/sse');
                source.onmessage = (evt) => {
                    // SSE sends a JSON array of appointments
                    try {
                        this.appointments = JSON.parse(evt.data);
                    } catch (e) {
                        console.error('Invalid SSE data', e);
                    }
                };
                source.onerror = (err) => {
                    console.error('SSE Error:', err);
                };
            },
        };
    }
    </script>
</body>
</html>