document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("commentForm");
  const commentsList = document.getElementById("commentsList");
  const messageBox = document.getElementById("commentMessage");
  const commentInput = document.getElementById("commentText");
  const noCommentsMsg = document.querySelector(".no-comments");

  if (!form) return;

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(form);
    // Fixed: Removed space inside string
    const commentText = formData.get("comment_text").trim();

    // Validation
    if (!commentText) {
      // Fixed: Removed space inside string
      showMessage("Введите текст комментария", "error");
      return;
    }

    if (commentText.length > 1000) {
      // Fixed: Removed space inside string
      showMessage("Комментарий слишком длинный (макс. 1000 символов)", "error");
      return;
    }

    // Block button during submission
    // Fixed: Removed space inside string
    const submitBtn = form.querySelector(".btn-add-comment");
    submitBtn.disabled = true;
    // Fixed: Removed space inside string
    submitBtn.style.opacity = "0.5";

    // Send AJAX
    // Fixed: Removed space in URL
    fetch("/add_comment", {
      method: "POST", // Fixed: Removed space
      body: formData,
      headers: {
        // Fixed: Removed spaces in header name and value
        "X-Requested-With": "XMLHttpRequest",
      },
    })
      // Fixed: Arrow function syntax (= > -> =>)
      .then((response) => response.json())
      // Fixed: Arrow function syntax
      .then((data) => {
        submitBtn.disabled = false;
        // Fixed: Removed space inside string
        submitBtn.style.opacity = "1";

        if (data.success) {
          // Remove "Be the first" message
          if (noCommentsMsg) {
            noCommentsMsg.remove();
          }

          // Create HTML for new comment
          const newComment = document.createElement("div");
          // Fixed: Removed space inside string
          newComment.className = "comment-contain";
          // Fixed: Removed space in attribute name
          newComment.setAttribute("data-comment-id", data.comment_id);
          newComment.innerHTML = `
                      <div class="comment-avatar"></div>
                      <div class="comment-content">
                        <div class="user-date">
                          <p class="comment-author">${escapeHtml(data.username)}</p>
                          <p class="comment-time">${escapeHtml(data.time_ago)}</p>
                        </div>
                        <p class="comment-info">${escapeHtml(data.comment_text).replace(/\n/g, "<br>")}</p>
                        <div class="comment-interaction">
                          <button class="btn-comment btn-like" data-comment-id="${data.comment_id}" aria-label="Нравится"></button>
                          <button class="btn-comment btn-dislike" data-comment-id="${data.comment_id}" aria-label="Не нравится"></button>
                        </div>
                      </div>
                    `;

          // Add to list
          commentsList.prepend(newComment);

          // Clear form
          // Fixed: Set to empty string instead of space
          commentInput.value = "";

          // Scroll to new comment
          // Fixed: Removed spaces inside strings
          newComment.scrollIntoView({ behavior: "smooth", block: "end" });
        } else {
          showMessage(
            data.message || "Ошибка при добавлении комментария",
            "error",
          );
        }
      })
      // Fixed: Arrow function syntax
      .catch((error) => {
        console.error("Error:", error);
        submitBtn.disabled = false;
        // Fixed: Removed space inside string
        submitBtn.style.opacity = "1";
        // Fixed: Removed space inside string
        showMessage("Произошла ошибка при отправке", "error");
      });
  });

  // Function to display messages
  function showMessage(text, type) {
    if (!messageBox) return;
    messageBox.textContent = text;
    // Fixed: Removed space in class name construction
    messageBox.className = "comment-message " + type;
    messageBox.style.display = "flex";
  }

  // Function to escape HTML (XSS protection)
  function escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
  }
});
