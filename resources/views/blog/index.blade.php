<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog (SPA) at /blog</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100 p-4" x-data="blogApp()">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow space-y-4">
        <h1 class="text-2xl font-bold mb-4">Blog</h1>
        <div class="flex gap-3 mb-4">
              <button class="px-4 py-2 bg-blue-500 text-white rounded"
                    :class="{ 'bg-blue-700': tab === 'form' }"
                    @click="tab = 'form'">
                Create Post
            </button>
            <button class="px-4 py-2 bg-blue-500 text-white rounded"
                    :class="{ 'bg-blue-700': tab === 'list' }"
                    @click="tab = 'list'">
                View Posts
            </button>
        </div>

            <!-- Form Tab -->
        <div x-show="tab === 'form'" class="space-y-4">
            <form @submit.prevent="createPost" class="space-y-3">
                <div>
                    <label class="block font-semibold">Title</label>
                    <input type="text" x-model="form.title"
                           class="border p-2 w-full" required>
                </div>
                <div>
                    <label class="block font-semibold">Content</label>
                    <textarea x-model="form.content"
                              class="border p-2 w-full" required rows="5"></textarea>
                </div>

                <button type="submit"
                        class="px-4 py-2 bg-green-500 text-white rounded">
                    Create Post
                </button>
            </form>
              <template x-if="message">
                <p class="text-green-600 font-semibold" x-text="message"></p>
            </template>
        </div>

            <!-- List Tab -->
            <div x-show="tab === 'list'">
            <h2 class="text-xl font-bold mb-3">Existing Blog Posts</h2>
            <ul class="space-y-2">
                <template x-for="item in blogs" :key="item.id">
                     <li class="p-3 bg-gray-50 rounded flex justify-between items-center">
                        <div>
                            <span class="font-semibold" x-text="item.title"></span><br>
                            <span class="text-sm text-gray-600" x-text="item.content.substring(0,40) + '...' "></span><br>
                                 <span class="text-sm font-medium"
                                  x-text="formatDate(item.created_at)"></span><br>
                        </div>
                           <div class="flex items-center gap-3">
                                <template x-if="item.is_new">
                                     <span class="inline-block w-3 h-3 rounded-full bg-green-500"></span>
                                </template>
                                <button class="text-blue-600 text-sm underline"
                                         @click="editPost(item)">
                                     Edit
                                </button>
                                <button class="text-red-600 text-sm underline"
                                        @click="deletePost(item.id)">
                                    Delete
                                </button>
                            </div>
                    </li>
                </template>
            </ul>
        </div>
            <!-- Edit Modal -->
            <template x-if="editing">
                <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                    <div class="bg-white p-4 rounded w-96 space-y-3">
                        <h3 class="text-lg font-bold">Edit Post</h3>
                        <form @submit.prevent="updatePost" class="space-y-3">
                            <div>
                                <label>Title</label>
                                <input type="text" x-model="editForm.title"
                                       class="border p-2 w-full" required>
                            </div>
                            <div>
                                <label>Content</label>
                                <textarea  x-model="editForm.content" class="border p-2 w-full" required  rows="5"></textarea>
                            </div>

                            <div class="flex justify-end gap-2">
                                <button type="button" @click="closeEdit"
                                        class="px-4 py-2 bg-gray-500 text-white rounded">
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

    <script>
    function blogApp() {
        return {
               tab:'form',
                blogs:[],
                message: '',
                form:{
                  title:'',
                  content:''
                 },
                editing:false,
                editId:null,
               editForm:{
                 title:'',
                 content:''
               },
                 async createPost(){
                      this.message = '';
                      const resp = await fetch('/blog', {
                           method: 'POST',
                           headers: {
                               'Content-Type': 'application/json',
                               'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                           },
                           body: JSON.stringify(this.form),
                       });
                       const json = await resp.json();
                       if (json.status === 'success') {
                           this.message = 'Successfully saved!';
                           this.form = { title: '', content:'' };
                           this.tab = 'list';
                           // Fetch data after create.
                         this.fetchBlogs();
                       } else {
                           alert('Failed to save.');
                       }
                  },
                 async fetchBlogs(){
                     const resp = await fetch('/blog/sse');
                       const reader = resp.body.getReader();
                       let text = "";
                       let jsonStr = "";
                        while (true) {
                           const { done, value } = await reader.read();
                           if (done) break;
                           text += new TextDecoder().decode(value);
                           if(text.includes('data:')){
                               const parts = text.split("data:");
                               jsonStr = parts[parts.length-1].trim();
                               if(jsonStr){
                                   try{
                                       this.blogs = JSON.parse(jsonStr);
                                       text = "";
                                   } catch(e){
                                       console.error('Error parsing json string' , e);
                                   }
                               }
                           }

                        }
                 },
                 editPost(item) {
                       this.editing = true;
                       this.editId = item.id;
                       this.editForm = {
                           title: item.title,
                           content: item.content
                       };
                   },
                closeEdit() {
                        this.editing = false;
                        this.editId = null;
                },
                async updatePost() {
                       const url = `/blog/${this.editId}`;
                        const resp = await fetch(url, {
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
                         this.fetchBlogs(); // Update the list
                       } else {
                          alert('Update failed.');
                       }
                   },
                async deletePost(id) {
                       if (!confirm('Are you sure you want to delete this?')) return;
                        const url = `/blog/${id}`;
                       const resp = await fetch(url, {
                           method: 'DELETE',
                           headers: {
                               'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                           },
                       });
                       const data = await resp.json();
                       if (data.status !== 'deleted') {
                           alert('Delete failed.');
                       }
                    this.fetchBlogs();
                   },
                 formatDate(dtStr) {
                    let d = new Date(dtStr);
                    return d.toLocaleString();
                  },
                init() {

                  this.fetchBlogs();

                    const source = new EventSource('/blog/sse');
                    source.onmessage = (e) => {
                        try {
                            this.blogs = JSON.parse(e.data);
                        } catch (err) {
                            console.error('SSE parse error:', err);
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