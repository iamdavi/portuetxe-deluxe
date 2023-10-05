export default class Post {
  baseUrl;
  likePostButtons;
  likeCommentButtons;
  commentCommentButtons;

  currentPostCommentCount;

  formPostCommentPostInput;

  constructor() {
    this.baseUrl = "localhost:8000";

    this.commentForm = document.querySelector("form[name='comment']");

    this.commentPostButtons = document.querySelectorAll('.comment-wrapper')

    this.formPostCommentPostInput = document.querySelector(
      "form[name='comment'] #comment_post"
    );

    this.likePostButtons = document.querySelectorAll("[data-like-post]");
    this.likeCommentButtons = document.querySelectorAll("[data-like-comment]");

    this.addEvents();
  }

  addEvents() {
    this.postLikeButtonHandler();
    this.commentLikeButtonHandler();
    this.commentPostButtonHandler();
  }

  commentPostButtonHandler() {
    for (let commentPostButton of this.commentPostButtons) {
      console.log('frki');
      commentPostButton.addEventListener(
        "click",
        function (e) {
          const postId = e.currentTarget.dataset.commentPost;
          this.formPostCommentPostInput.value = postId;
          this.currentPostCommentCount = e.currentTarget;
        }.bind(this)
      );
    }

    this.commentForm.addEventListener(
      "submit",
      async function (e) {
        e.preventDefault();
        const payload = new FormData(e.target);
        const req = await fetch("/", {
          method: "POST",
          body: payload,
        });
        const res = await req.json();
        if (res.status == "success") {
          const dismissModalButton = document.getElementById(
            "dissmissCommentModal"
          );
          // Dismiss modal and reset comment text
          dismissModalButton.dispatchEvent(new Event("click"));
          document.getElementById("comment_text").value = "";
          // Show toast
          const toast = document.getElementById("commentAddedPost");
          toast.classList.add("show");
          // Update post comment
          const commentCounterWrapper = this.currentPostCommentCount
            .querySelector(".comment-counter");
          commentCounterWrapper.innerText =
            parseInt(commentCounterWrapper.innerText) + 1;
        }
      }.bind(this)
    );
  }

  postLikeButtonHandler() {
    for (let likePostButton of this.likePostButtons) {
      likePostButton.addEventListener(
        "click",
        function (e) {
          this.likeButtonCall(e, "/posts/like/", "post");
        }.bind(this)
      );
    }
  }

  commentLikeButtonHandler() {
    for (let likeCommentButton of this.likeCommentButtons) {
      likeCommentButton.addEventListener(
        "click",
        function (e) {
          this.likeButtonCall(e, "/comments/like/", "comment");
        }.bind(this)
      );
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
      comment: "likeComment",
      post: "likePost",
    };

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
