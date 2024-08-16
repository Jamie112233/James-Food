document.addEventListener("click", async (e) => {
  if (e.target.dataset.action === "manage visibility") {
    e.preventDefault();

    if (e.target.classList.contains("bi-eye-slash-fill")) {
      let formData = new FormData();
      formData.append("comment_id", e.target.dataset.id);
      formData.append("action", "hide");

      const options = {
        body: formData,
        method: "post",
      };

      let response = await fetch("manage-comment-visibility.php", options);
      let data = await response.json();

      if (data.error) {
        alert(data.message);
        return;
      }

      e.target.closest(
        "span"
      ).innerHTML = `<a href="#" class="me-2 text-warning" title="Show this comment"><i class="bi bi-eye-fill" data-action="manage visibility" data-id="${e.target.dataset.id}"></i></a>`;
    } else if (e.target.classList.contains("bi-eye-fill")) {
      let formData = new FormData();
      formData.append("comment_id", e.target.dataset.id);
      formData.append("action", "show");

      const options = {
        body: formData,
        method: "post",
      };

      let response = await fetch("manage-comment-visibility.php", options);
      let data = await response.json();

      if (data.error) {
        alert(data.message);
        return;
      }

      e.target.closest(
        "span"
      ).innerHTML = `<a href="#" class="me-2 text-warning" title="Hide this comment"><i class="bi bi-eye-slash-fill" data-action="manage visibility" data-id="${e.target.dataset.id}"></i></a>`;
    }
  } else if (e.target.dataset.action === "delete comment") {
    e.preventDefault();
    if (confirm("Are you sure you want to delete this comment?")) {
      e.target.closest("form").submit();
    }
  }
});

const content = document.getElementById("content");
content.addEventListener("input", (e) => {
  content.closest("form").submit();
});
