<template>
    <form @submit.prevent="loginData">
        <div class="custom_form">
            <h6 class="mb-0 text-uppercase">Login Form</h6>
            <hr />
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input
                    type="email"
                    class="form-control custom-input"
                    id="exampleInputEmail1"
                    placeholder="Enter email"
                    v-model="formData.email"
                    required
                />
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input
                    type="password"
                    class="form-control custom-input"
                    name="password"
                    id="exampleInputPassword1"
                    placeholder="Password"
                    v-model="formData.password"
                    required
                />
            </div>
            <div
                class="d-flex align-items-center justify-content-between mt-20px"
            >
                <button type="submit" class="btn btn-primary">Submit</button>
                <div class="card-header">
                    <router-link
                        :to="{ name: 'Register' }"
                        class="btn btn-info"
                        data-bs-toggle="modal"
                        data-bs-target="#exampleModal"
                        >Create New Account</router-link
                    >
                </div>
            </div>
        </div>
    </form>
</template>

<script>
import { showToast } from "../showToast";
export default {
    data() {
        return {
            formData: {
                email: "",
                password: "",
            },
            formError: {
                email: "",
                password: "",
            },
        };
    },
    mount: {},
    methods: {
        loginData() {
            axios
                .post("/api/auth/login", this.formData)
                .then((response) => {
                    const token = response.data.access_token;
                    if (token) {
                        localStorage.setItem("authToken", token);

                        this.$router.push({ name: "List" });
                        showToast("Login successfully", "success");
                    } else {
                        console.log("Token is undefined or null");
                    }
                })
                .catch((error) => {
                    if (error.response && error.response.data.errors) {
                        this.formError.email =
                            error.response.data.errors.email || "";
                        this.formError.password =
                            error.response.data.errors.password || "";
                        const errorMessage =
                            error.response.data.message || "An error occurred";
                        showToast(`Error: ${errorMessage}`, "error");

                        this.$router.push({ name: "Login" });
                    }
                });
        },
    },
};
</script>
<style>
input {
    width: 120px;
}

.custom-input {
    max-width: 300px;
    margin: 0 auto;
}

.custom_form {
    width: 100%;
    max-width: 500px;
    margin: 0 auto;
    padding: 20px;
    border-radius: 5px;
    background-color: #f2f2f2;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
}
.form-group {
    display: flex;
    flex-direction: column;
}
.form-group label {
    margin-bottom: 5px;
}
</style>
