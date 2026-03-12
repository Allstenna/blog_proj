document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("deleteModal");
  const deleteButtons = document.querySelectorAll(".delete-btn");
  const closeBtn = document.querySelector(".modal-close");
  const confirmBtn = document.querySelector(".btn-delete-confirm");
  const modalText = document.querySelector(".modal-text");
  const modalTitle = document.querySelector(".modal-title");
  let postIdToDelete = null;

  // Сохраняем оригинальные текст и заголовок
  const originalText = modalText.textContent;
  const originalTitle = modalTitle.textContent;

  // Открытие модального окна при клике на кнопку удаления
  deleteButtons.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      postIdToDelete = e.target.dataset.postId;

      // Сбрасываем состояние модального окна
      modalText.textContent = originalText;
      modalTitle.textContent = originalTitle;
      confirmBtn.style.display = "inline-block";
      confirmBtn.disabled = false;
      confirmBtn.textContent = "Удалить";
      modal.classList.add("active");
    });
  });

  // Закрытие модального окна
  function closeModal() {
    modal.classList.remove("active");
    postIdToDelete = null;

    // Сбрасываем все изменения
    modalText.textContent = originalText;
    modalTitle.textContent = originalTitle;
    confirmBtn.style.display = "inline-block";
    confirmBtn.disabled = false;
    confirmBtn.textContent = "Удалить";
  }

  closeBtn.addEventListener("click", closeModal);

  // Подтверждение удаления
  confirmBtn.addEventListener("click", () => {
    if (!postIdToDelete) return;

    fetch(`/deletePost?id=${postIdToDelete}`, {
      method: "DELETE",
    })
      .then((response) => {
        if (response.ok) {
          modalTitle.textContent = "Готово";
          modalText.textContent = "Пост успешно удален";

          // Скрываем кнопку
          confirmBtn.style.display = "none";

          // Удаляем строку из таблицы
          const row = document
            .querySelector(`.delete-btn[data-post-id="${postIdToDelete}"]`)
            .closest("tr");
          row.remove();
        } else {
          // Ошибка при удалении
          modalTitle.textContent = "Ошибка";
          modalText.textContent =
            "Не удалось удалить пост. Попробуйте ещё раз.";

          // Возвращаем кнопку в исходное состояние
          confirmBtn.disabled = false;
          confirmBtn.textContent = "Удалить";
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        modalTitle.textContent = "Ошибка";
        modalText.textContent =
          "Произошла ошибка. Проверьте соединение с интернетом.";
        confirmBtn.disabled = false;
        confirmBtn.textContent = "Удалить";
      });
  });
});