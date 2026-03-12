document.addEventListener("click", function (event) {
  // Если кликнули на кнопку like
  if (event.target.classList.contains("btn-like")) {
    const btnLike = event.target;
    btnLike.classList.toggle("active");

    const parentBlock = btnLike.closest(".comment-interaction");
    const btnDislike = parentBlock.querySelector(".btn-dislike");
    if (btnDislike) {
      btnDislike.classList.remove("active");
    }
  }

  // Если кликнули на кнопку dislike
  if (event.target.classList.contains("btn-dislike")) {
    const btnDislike = event.target;
    const parentBlock = btnDislike.closest(".comment-interaction");
    const btnLike = parentBlock.querySelector(".btn-like");
    if (btnLike) {
      btnLike.classList.remove("active");
    }
    btnDislike.classList.toggle("active");
  }
});

let btnLikePost = document.getElementById("btn-post-like");
let btnSavedPost = document.getElementById("btn-post-saved");

btnLikePost.addEventListener("click", () => {
  btnLikePost.classList.toggle("active");
});
btnSavedPost.addEventListener("click", () => {
  btnSavedPost.classList.toggle("active");
});