<template>
    <div class="container">
        <div class="row">
            <div class="col-md-6 m-auto">
                <div class="card">
                    <h2>Edit Role</h2>
                    <div class="card-body">
                        <form @submit.prevent="updateData">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="formData.name"
                                />
                                <span
                                    class="text-danger"
                                    v-for="(error, index) in formError.name"
                                    :key="index"
                                    >{{ error }}</span
                                >
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Permissions</label>
                                <div
                                    v-for="(permission, index) in permissions"
                                    :key="permission.id"
                                    class="form-check"
                                >
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            :value="permission.id"
                                            :id="
                                                'flexCheckDefault-' +
                                                permission.id
                                            "
                                            v-model="formData.permissions"
                                        />
                                        <label
                                            class="form-check-label"
                                            for="flexCheckDefault"
                                        >
                                            {{ permission.name }}
                                        </label>
                                    </div>
                                </div>
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
                name: "",
                permissions: [],
            },
            formError: {
                name: [],
                permissions: [],
            },
            permissions: [],
        };
    },
    mounted() {
        this.permissionList();
        this.edit();
    },
    methods: {
        edit() {
            const token = localStorage.getItem("authToken");
            if (token) {
                axios
                    .get("/api/auth/role/show/" + this.$route.params.id, {
                        headers: {
                            Authorization: "Bearer " + token,
                        },
                    })
                    .then((response) => {
                        const role = response.data.role;
                        this.formData.name = role.name;
                        this.formData.permissions = role.permissions.map(
                            (perm) => perm.id
                        );
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            } else {
                this.$router.push({ name: "Login" });
                return;
            }
        },

        permissionList() {
            const token = localStorage.getItem("authToken");
            if (token) {
                axios
                    .get("/api/auth/role/allPermission", {
                        headers: {
                            Authorization: "Bearer " + token,
                        },
                    })
                    .then((response) => {
                        console.log(response);
                        this.permissions = response.data.permissions;
                    })
                    .catch((error) => {
                        console.log(error);
                        // this.$router.push("/login");
                    });
            } else {
                this.$router.push("/login");
                return;
            }
        },

        updateData() {
            const token = localStorage.getItem("authToken");
            const formData = new FormData();

            // Append 'name' correctly
            formData.append("name", this.formData.name);

            this.formData.permissions.forEach((permission, index) => {
                formData.append(`permissions[${index}]`, permission);
            });

            if (token) {
                axios
                    .post(
                        `/api/auth/role/update/${this.$route.params.id}`,
                        formData,
                        {
                            headers: {
                                Authorization: "Bearer " + token,
                                "Content-Type": "application/json",
                            },
                        }
                    )
                    .then((response) => {
                        console.log(response);
                        this.$router.push({ name: "PermissionList" });
                    })
                    .catch((error) => {
                        console.log(
                            error.response ? error.response.data : error.message
                        );
                    });
            } else {
                this.$router.push({ name: "Login" });
            }
        },
    },
};
</script>
