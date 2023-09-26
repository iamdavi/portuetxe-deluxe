export default class Post {
  baseUrl;
  likePostButtons;
  likeCommentButtons;

  constructor() {
    this.baseUrl = "localhost:8000";

    this.likePostButtons = document.querySelectorAll("[data-like-post]");
    this.likeCommentButtons = document.querySelectorAll("[data-like-comment]");

    this.addEvents();
  }

  addEvents() {
    this.postLikeButtonHandler();
    this.commentLikeButtonHandler();
  }

  postLikeButtonHandler() {
    for (let likePostButton of this.likePostButtons) {
      likePostButton.addEventListener("click", function (e) {
        this.likeButtonCall(e, '/posts/like/', 'post')
      }.bind(this));
    }
  }

  commentLikeButtonHandler() {
    for (let likeCommentButton of this.likeCommentButtons) {
      likeCommentButton.addEventListener("click", function (e) {
        this.likeButtonCall(e, '/comments/like/', 'comment')
      }.bind(this));
    }
  }

  async likeButtonCall(e, url, type) {
    const target = e.target.classList.contains("like-wrapper")
      ? e.target
      : e.target.closest(".like-wrapper");
    const icon = target.querySelector(".bi");
    if (icon.classList.contains("bi-heart-fill")) {
      return;
    }

    const datasetPropertyByType = {
      'comment': 'likeComment',
      'post': 'likePost'
    }

    const objectId = target.dataset[datasetPropertyByType[type]];
    const req = await fetch(url + objectId, {
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
  }
}
