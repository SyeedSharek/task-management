<template>
    <div class="container-fluid">
        <div class="row p-0">
            <div class="col-2 sidebar p-0">
                <h4>Admin Dashboard</h4>
                <div
                    v-for="checkRole in roleWiserPermissions"
                    :key="checkRole.id"
                    class="list-group list-group-flush text-bold"
                >
                    <router-link
                        v-if="checkRole.name === 'task list'"
                        :to="{ name: 'List' }"
                        class="list-group-item list-group-item-action list-group-item-dark active"
                        active-class="active"
                        >Task List</router-link
                    >
                    <a
                        v-if="checkRole.name === 'dashboard'"
                        href="#"
                        class="list-group-item list-group-item-action list-group-item-dark"
                        >Dashboard</a
                    >

                    <router-link
                        v-if="checkRole.name === 'permission list'"
                        :to="{ name: 'PermissionList' }"
                        class="list-group-item list-group-item-action list-group-item-dark"
                        active-class="active"
                        >Permission List</router-link
                    >

                    <router-link
                        v-if="checkRole.name === 'user list'"
                        :to="{ name: 'ListUserRole' }"
                        class="list-group-item list-group-item-action list-group-item-dark"
                        active-class="active"
                        >User List</router-link
                    >
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-10 main-contain m-0 p-0">
                <nav class="navbar bg-body-tertiary">
                    <div class="container-fluid">
                        <a href="#" class="navbar-brand"
                            >Task Management Project</a
                        >

                        <button
                            @click.prevent="logout"
                            class="btn btn-outline-success"
                            type="submit"
                        >
                            Logout
                        </button>
                    </div>
                </nav>

                <div class="main-view">
                    <router-view></router-view>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            message: "Welcome to the Desh Board!",
            roleWiserPermissions: [],
            tasks: [],
        };
    },
    mounted() {
        this.taskList();
        this.RoleWisePermission();
    },
    methods: {
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
                        console.log(response.data.tasks);

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
        RoleWisePermission() {
            const token = localStorage.getItem("authToken");
            if (token) {
                axios
                    .get("/api/auth/user/rolewisepermission", {
                        headers: { Authorization: `Bearer ${token}` },
                    })
                    .then((response) => {
                        console.log(response.data.permissions);
                        this.roleWiserPermissions = response.data.permissions;
                    })
                    .catch((error) => {
                        console.error("Error fetching permissions", error);
                    });
            }
        },
    },
};
</script>
<style>
html,
body {
    margin: 0;
    padding: 0;
    height: 100%;
    overflow: hidden;
}
.sidebar {
    height: 100vh;
    background: none;
    background-color: #777;
    overflow-y: auto;
}
.list-group-item {
    background-color: transparent;
    color: #ddd;
}
.logout-btn {
    margin-left: auto;
}
.main-contain {
    height: 100vh;
    overflow-y: auto;
}
</style>
