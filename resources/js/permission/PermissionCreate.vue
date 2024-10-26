<template>
    <div class="container">
        <div class="row">
            <div class="col-md-6 m-auto">
                <div class="card">
                    <h2>Create Role</h2>
                    <div class="card-body">
                        <form @submit.prevent="storeData">
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
import { toRaw } from "vue";

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
    },
    methods: {
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
        storeData() {
            const token = localStorage.getItem("authToken");

            if (token) {
                axios
                    .post(
                        "/api/auth/role/store",
                        {
                            name: this.formData.name,
                            permissions: this.formData.permissions,
                        },
                        {
                            headers: {
                                Authorization: "Bearer " + token,
                            },
                        }
                    )
                    .then((response) => {
                        console.log(response);
                        this.$router.push({ name: "PermissionList" });
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            } else {
                this.$router.push({ name: "Login" });
                return;
            }
        },
    },
};
</script>
