<template>
    <div class="container">
        <div class="row">
            <div class="col-md-6 m-auto">
                <div class="card">
                    <div class="card-header">
                        <h2>Update Task</h2>
                    </div>
                    <div class="card-body">
                        <form
                            @submit.prevent="updateData"
                            enctype="multipart/form-data"
                        >
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="formData.title"
                                />
                                <span
                                    class="text-danger"
                                    v-for="(error, index) in formError.title"
                                    :key="index"
                                    >{{ error }}</span
                                >
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="formData.description"
                                />
                                <span
                                    class="text-danger"
                                    v-for="(
                                        error, index
                                    ) in formError.description"
                                    :key="index"
                                    >{{ error }}</span
                                >
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Due Date</label>
                                <input
                                    type="date"
                                    class="form-control"
                                    v-model="formData.due_date"
                                />
                                <span
                                    class="text-danger"
                                    v-for="(error, index) in formError.due_date"
                                    :key="index"
                                    >{{ error }}</span
                                >
                            </div>
                            <div class="input-group mb-3">
                                <label
                                    class="input-group-text"
                                    for="inputGroupSelect01"
                                    >Select User</label
                                >
                                <select
                                    class="form-select custom-scroll"
                                    id="inputGroupSelect01"
                                    v-model="formData.user_id"
                                >
                                    <option value="" disabled>Choose...</option>
                                    <!-- Loop through tasks and display the user_name for each task -->
                                    <option
                                        v-for="user in users"
                                        :key="user.id"
                                        :value="user.id"
                                    >
                                        {{ user.name }}
                                    </option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Image</label>
                                <input
                                    type="file"
                                    class="form-control"
                                    @change="handleFileUpload"
                                />
                                <span
                                    class="text-danger"
                                    v-for="(error, index) in formError.image"
                                    :key="index"
                                    >{{ error }}</span
                                >
                            </div>

                            <button type="submit" class="btn btn-primary">
                                Submit
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            formData: {
                title: "",
                description: "",
                due_date: "",
                user_id: "",
                image: null,
            },
            formError: {
                title: [],
                description: [],
                due_date: [],
                user_id: "",
                image: [],
            },
            users: [],
        };
    },
    mounted() {
        this.edit();
    },
    methods: {
        edit() {
            const token = localStorage.getItem("authToken");
            if (token) {
                axios
                    .get("/api/auth/task/show/" + this.$route.params.id, {
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    })
                    .then((response) => {
                        if (response.data && response.data.task) {
                            this.formData = response.data.task;

                            if (response.data.task.users) {
                                this.formData.user_id =
                                    response.data.task.users.id;
                            }
                            this.users = response.data.users;
                        } else {
                            console.error("No task data found");
                        }
                    })
                    .catch((error) => {
                        console.error("Error fetching product data:", error);
                        this.formData = {
                            title: "",
                            description: "",
                            due_date: "",
                            user_id: "",
                            image: null,
                        }; // fallback
                    });
            }
        },
        handleFileUpload(event) {
            const file = event.target.files[0];

            this.formData.image = file ? file : null;
        },
        updateData() {
            const token = localStorage.getItem("authToken");
            if (token) {
                const formData = new FormData();
                formData.append("title", this.formData.title);
                formData.append("description", this.formData.description);
                formData.append("due_date", this.formData.due_date);
                formData.append("user_id", this.formData.user_id);
                if (this.formData.image) {
                    formData.append("image", this.formData.image);
                }

                axios
                    .post(
                        `/api/auth/task/update/${this.$route.params.id}`,
                        formData,
                        {
                            headers: {
                                Authorization: `Bearer ${token}`,
                                "Content-Type": "multipart/form-data",
                            },
                        }
                    )
                    .then((response) => {
                        this.$router.push({ name: "List" });
                    })
                    .catch((error) => {
                        console.log(error.response.data);
                    });
            }
        },
    },
};
</script>
