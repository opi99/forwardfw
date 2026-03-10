document.addEventListener("DOMContentLoaded", () => {

    document.querySelectorAll(".dam-upload").forEach(container => {

        const fileInput = container.querySelector(".dam-file-input");
        const hiddenInput = container.querySelector("input[type=hidden]");
        const preview = container.querySelector(".dam-preview");

        fileInput.addEventListener("change", async () => {

            const file = fileInput.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append("file", file);

            try {

                const response = await fetch("/administration/dam/upload", {
                    method: "POST",
                    body: formData
                });

                const data = await response.json();

                if (!data.success) {
                    alert("Upload failed: " + data.error);
                    fileInput.value = "";
                    return;
                }

                hiddenInput.value = data.id;

                preview.innerHTML =
                    `<img src="${data.url}" style="max-width:200px">`;

            } catch (e) {
                console.error(e);
                fileInput.value = "";
                alert("Upload error");
            }

        });

    });

});
