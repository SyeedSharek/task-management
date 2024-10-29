// src/utils/showToast.js
import { toast } from "vue3-toastify";

export function showToast(message, type = "success") {
    toast(message, {
        type,
        position: "top-right",
        autoClose: 1000,
        hideProgressBar: false,
        closeOnClick: true,
        pauseOnHover: true,
        draggable: true,
    });
}
