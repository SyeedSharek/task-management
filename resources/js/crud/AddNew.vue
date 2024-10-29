<template>
    <div class="container">
        <div class="row">
            <div class="col-md-6 m-auto">
                <div class="card">
                    <div class="card-header">
                        <router-link
                            :to="{ name: 'List' }"
                            class="btn btn-primary"
                            >List View</router-link
                        >
                    </div>
                    <div class="card-body">
                        <form
                            @submit.prevent="storeData"
                            enctype="multipart/form-data"
                        >
                            <div class="mb-3">
                                <label class="form-label">Tittle</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="formData.title"
                                    required
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
                                    required
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
                                    required
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
                                    required
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
import { showToast } from "../showToast";

export default {
    data() {
        return {
            formData: {
                title: "",
                description: "",
                due_date: "",
                user_name: "",
                image: null,
            },
            formError: {
                title: [],
                description: [],
                due_date: [],
                user_id: [],
                image: [],
            },
            users: [],
        };
    },

    mounted() {
        this.getTasks();
    },
    methods: {
        getTasks() {
            const token = localStorage.getItem("authToken");
            if (token) {
                axios
                    .get("/api/auth/user/index", {
                        headers: {
                            Authorization: `Bearer ${token}`, // Send token in Authorization header
                        },
                    })
                    .then((response) => {
                        this.users = response.data.users;
                    })
                    .catch((error) => {
                        console.error("Error fetching tasks:", error);
                    });
            }
        },
        handleFileUpload(event) {
            const file = event.target.files[0];

            this.formData.image = file ? file : null;
        },

        storeData() {
            const token = localStorage.getItem("authToken");
            const userId = this.formData.user_id;

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
                    .post("/api/auth/task/store", formData, {
                        headers: {
                            Authorization: `Bearer ${token}`,
                            "Content-Type": "multipart/form-data",
                        },
                    })
                    .then((response) => {
                        console.log(response);
                        this.$router.push({ name: "List" });
                        showToast("Login successfully", "success");
                    })
                    .catch((error) => {
                        console.log(error.response.data);
                    });
            } else {
                this.$router.push({ name: "Login" });
                return;
            }
        },
    },
};
</script>

<style>
/* Custom CSS to limit the height of the select dropdown */
.custom-scroll {
    max-height: 150px; /* Approx height for 4 options */
    overflow-y: auto; /* Enable vertical scrolling if content exceeds */
}
</style>
