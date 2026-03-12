document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("deleteModal");
  const deleteButtons = document.querySelectorAll(".delete-btn");
  const closeBtn = document.querySelector(".modal-close");
  const confirmBtn = document.querySelector(".btn-delete-confirm");
  const modalText = document.querySelector(".modal-text");
  const modalTitle = document.querySelector(".modal-title");
  
  let commentIdToDelete = null;
  let rowToDelete = null; // 💡 храним ссылку на строку сразу

  const originalText = modalText.textContent;
  const originalTitle = modalTitle.textContent;

  deleteButtons.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault(); // 🔹 на всякий случай
      
      // ✅ Используем currentTarget и правильное имя атрибута
      commentIdToDelete = e.currentTarget.dataset.commentId;
      
      // 💡 Сохраняем строку сразу — не придётся искать потом
      rowToDelete = e.currentTarget.closest("tr");
      
      // Сброс состояния модалки
      modalText.textContent = originalText;
      modalTitle.textContent = originalTitle;
      confirmBtn.style.display = "inline-block";
      confirmBtn.disabled = false;
      confirmBtn.textContent = "Удалить";
      
      modal.classList.add("active");
    });
  });

  function closeModal() {
    modal.classList.remove("active");
    commentIdToDelete = null;
    rowToDelete = null;
    
    modalText.textContent = originalText;
    modalTitle.textContent = originalTitle;
    confirmBtn.style.display = "inline-block";
    confirmBtn.disabled = false;
    confirmBtn.textContent = "Удалить";
  }

  closeBtn.addEventListener("click", closeModal);
  
  // Закрытие по клику вне окна
  modal.addEventListener("click", (e) => {
    if (e.target === modal) closeModal();
  });

  confirmBtn.addEventListener("click", () => {
    if (!commentIdToDelete) return;

    // Блокируем кнопку на время запроса
    confirmBtn.disabled = true;
    confirmBtn.textContent = "Удаление...";

    fetch(`/deleteComment?id=${commentIdToDelete}`, {
      method: "DELETE",
      headers: {
        'Content-Type': 'application/json'
      }
    })
      .then(async (response) => {
        // 🔍 Читаем ответ для отладки
        const result = await response.json().catch(() => ({}));
        console.log("Server response:", response.status, result);
        
        if (response.ok) {
          modalTitle.textContent = "Готово";
          modalText.textContent = "Комментарий успешно удалён";
          
          confirmBtn.style.display = "none";
          
          rowToDelete?.remove();
          
          setTimeout(closeModal, 1500);
        } else {
          throw new Error(result.error || "Ошибка сервера");
        }
      })
      .catch((error) => {
        console.error("Delete error:", error);
        modalTitle.textContent = "Ошибка";
        modalText.textContent = error.message || "Не удалось удалить. Проверьте соединение.";
        
        // Возвращаем кнопку
        confirmBtn.disabled = false;
        confirmBtn.textContent = "Удалить";
      });
  });
});