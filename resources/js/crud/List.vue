<template>
    <div class="card">
        <div
            class="card-header d-flex align-items-center justify-content-between"
        >
            <router-link :to="{ name: 'AddNew' }" class="btn btn-primary"
                >Add New</router-link
            >
        </div>
        <div class="card-body">
            <div class="input-group mb-3" style="width: 250px; float: right">
                <span class="input-group-text" id="basic-addon1"
                    ><i class="fas fa-search"></i
                ></span>
                <input
                    type="text"
                    class="form-control"
                    placeholder="Search Here"
                    aria-label="Search"
                    aria-describedby="basic-addon1"
                    v-model="searchQuery"
                    @keyup="search"
                />
            </div>
            <table class="table overflow-auto">
                <thead>
                    <tr>
                        <th scope="col">SL</th>
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                        <th scope="col">Due Date</th>
                        <th scope="col">User Name</th>
                        <th scope="col">Image</th>
                        <th scope="col ">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        style="height: 100px"
                        v-for="(task, index) in tasks"
                        :key="task.id"
                    >
                        <th scope="row">{{ index + 1 }}</th>
                        <td>{{ task.title }}</td>
                        <td>{{ task.description }}</td>
                        <td>{{ task.due_date }}</td>
                        <td class="text-capitalize">
                            {{ task.user ? task.user.name : "No User" }}
                        </td>
                        <td>
                            <img
                                v-if="task.image"
                                :src="`/images/${task.image}`"
                                alt="No Image"
                                width="80"
                                height="80"
                            />
                            <span v-else>No Image</span>
                        </td>

                        <td>
                            <span>{{ task.status }}</span>
                            <select
                                v-model="task.status"
                                @change="updateStatus(task.id, task.status)"
                            >
                                <option value="Pending">Pending</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </td>

                        <td>
                            <router-link
                                :to="{
                                    name: 'Edit',
                                    params: { id: task.id },
                                }"
                                class="btn btn-primary m-1"
                                ><i class="fa-solid fa-pen-to-square"></i>
                            </router-link>
                            <a @click="destroy(task.id)" class="btn btn-danger"
                                ><i class="fa-solid fa-trash"></i
                            ></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
import axios from "axios";

export default {
    data() {
        return {
            tasks: [],
            searchQuery: "",
        };
    },
    mounted() {
        this.taskList();
    },
    methods: {
        taskList() {
            const token = localStorage.getItem("authToken");
            if (token) {
                axios
                    .get("/api/auth/task/index", {
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    })
                    .then((response) => {
                        this.tasks = response.data.tasks;
                    })
                    .catch((error) => {
                        console.log("Data Error", error);
                    });
            } else {
                this.$router.push({ name: "Login" });
                return;
            }
        },

        destroy(task_id) {
            const token = localStorage.getItem("authToken");
            if (token) {
                axios
                    .delete(`/api/auth/task/delete/${task_id}`, {
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    })
                    .then((success) => {
                        this.taskList();
                    })
                    .catch((error) => {
                        console.log("Data Error");
                    });
            } else {
                this.$router.push({ name: "Login" });
                return;
            }
        },
        updateStatus(task_id, status) {
            const token = localStorage.getItem("authToken");
            if (token) {
                axios
                    .put(
                        "/api/auth/task/status/" + task_id,
                        { status: status },
                        {
                            headers: {
                                Authorization: `Bearer ${token}`,
                            },
                        }
                    )
                    .then((success) => {
                        this.taskList();
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            } else {
                this.$router.push({ name: "Login" });
                return;
            }
        },
        logout() {
            const token = localStorage.getItem("authToken");
            axios
                .post(
                    "/api/auth/logout",
                    {},

                    {
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    }
                )
                .then(() => {
                    localStorage.removeItem("authToken");
                    this.$router.push({ name: "Login" });
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        search() {
            const token = localStorage.getItem("authToken");
            // const value = e.target.value;
            const value = this.searchQuery;
            if (token) {
                axios
                    .get(`/api/auth/task/search?search=${value}`, {
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    })
                    .then((response) => {
                        this.tasks = response.data.task;
                    })
                    .catch((error) => {
                        console.log(
                            "Search error:",
                            error.response ? error.response.data : error.message
                        );
                        this.tasks = [];
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
.table-wrapper {
    max-height: 300px; /* Adjust height as needed */
    overflow-y: auto;
    overflow-x: hidden; /* Prevent horizontal scrollbar */
}

.table {
    width: 100%;
    margin-bottom: 0; /* Ensure no margin is affecting the height */
}
</style>
