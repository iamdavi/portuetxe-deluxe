export default class Post {
  baseUrl;
  likePostButtons;

  constructor() {
    this.baseUrl = "localhost:8000";
    this.likePostButtons = document.querySelectorAll("[data-like-post]");

    this.addEvents();
  }

  addEvents() {
    for (let likePostButton of this.likePostButtons) {
      likePostButton.addEventListener("click", async function (e) {
        const target = e.target.classList.contains("like-wrapper")
          ? e.target
          : e.target.closest(".like-wrapper");
        const icon = target.querySelector(".bi");
        if (icon.classList.contains("bi-heart-fill")) {
          return;
        }
        const postId = target.dataset.likePost;
        const req = await fetch("/posts/like/" + postId, {
          method: "POST",
        });
        const res = await req.json();
        if (res.status == "success") {
          // Change Icon and color
          icon.classList.add(...["bi-heart-fill", "text-danger"]);
          icon.classList.remove("bi-heart");
          // Update counter
          const counterWrapper = target.querySelector(".like-counter");
          const counter = parseInt(counterWrapper.innerText) + 1;
          counterWrapper.innerText = counter;
        }
      });
    }
  }
}
