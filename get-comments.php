<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>IdeaStreamX - Projects</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white font-sans">
  <header class="bg-gradient-to-r from-purple-800 to-indigo-600 text-white py-6 px-8 flex items-center justify-between w-full shadow-xl rounded-b-xl">
    <a href="project.html" class="flex items-center space-x-3">
      <img src="logo.ico" alt="Logo" class="w-12 h-12 rounded-full shadow-lg" />
      <h1 class="text-3xl font-extrabold tracking-wide">IdeaStreamX</h1>
    </a>
    <nav id="auth-section" class="relative"></nav>
  </header>

  <main class="container mx-auto mt-12 px-6">
    <h2 class="text-4xl font-bold mb-10 text-center text-purple-300">Business Ideas</h2>

    <div id="video-container" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 mb-12"></div>
    <div class="flex justify-center flex-wrap gap-3 mb-32" id="pagination"></div>
  </main>

  <footer class="bg-gray-900 py-6 text-center border-t border-gray-800">
    <div id="post-button" class="mb-4"></div>
    <div class="flex justify-center gap-6 text-sm text-gray-400">
      <a href="about.html" class="hover:underline">About</a>
      <a href="services.html" class="hover:underline">Services</a>
      <a href="contact.html" class="hover:underline">Contact</a>
    </div>
  </footer>

  <script>
    const videosPerPage = 4;
    let currentPage = 1;
    let videos = [];

    async function loadVideos() {
      const res = await fetch('get-videos.php');
      const data = await res.json();
      videos = data.map(v => ({
        id:          v.id,
        title:       v.title,
        description: v.description,
        video_url:   v.video_url.includes('<iframe') 
                       ? v.video_url 
                       : `<iframe width="560" height="315" src="${v.video_url}" class="w-full aspect-video rounded" frameborder="0" allowfullscreen></iframe>`,
        view_count:  v.view_count,
        likes:       v.likes,
        comments:    v.comments
      }));
      showPage(currentPage);
      setupPagination();
    }

    function showPage(page) {
      currentPage = page;
      const container = document.getElementById('video-container');
      container.innerHTML = '';

      const start = (page - 1) * videosPerPage;
      const end   = start + videosPerPage;
      const slice = videos.slice(start, end);

      slice.forEach(v => {
        const card = document.createElement('div');
        card.className = 'bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-700 hover:shadow-2xl transition-shadow duration-300';
        card.innerHTML = `
          <h3 class="text-2xl font-semibold text-purple-400 mb-3">${v.title}</h3>
          <p class="text-gray-300 mb-4 text-sm leading-relaxed">${v.description}</p>
          ${v.video_url}
          <div class="mt-4 flex justify-between items-center text-sm text-gray-400">
            <button data-id="${v.id}" class="view-btn hover:text-white">👁 ${v.view_count}</button>
            <button data-id="${v.id}" class="like-btn hover:text-white">👍 ${v.likes}</button>
            <button data-id="${v.id}" class="comment-toggle hover:text-white">💬 ${v.comments}</button>
          </div>
          <div class="comment-box hidden mt-2">
            <input type="text" placeholder="Add comment…" class="w-full p-2 rounded bg-gray-800 text-white comment-input"/>
            <button data-id="${v.id}" class="submit-comment mt-2 bg-purple-600 px-3 py-1 rounded">Post</button>
            <div class="comments-list mt-4 space-y-2"></div>
          </div>
        `;
        container.appendChild(card);
      });

      bindVideoButtons();
    }

    function setupPagination() {
      const total = Math.ceil(videos.length / videosPerPage);
      const pagination = document.getElementById('pagination');
      pagination.innerHTML = '';

      for (let i = 1; i <= total; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        btn.className = `px-4 py-2 rounded ${i === currentPage ? 'bg-purple-700 text-white' : 'bg-gray-700 text-gray-200'} hover:bg-purple-800`;
        btn.onclick = () => { showPage(i); setupPagination(); };
        pagination.appendChild(btn);
      }
    }

    function timeAgo(dateStr) {
      const diff = Math.floor((new Date() - new Date(dateStr)) / 60000);
      if (diff < 1) return 'just now';
      if (diff < 60) return `${diff} min${diff > 1 ? 's' : ''} ago`;
      const d = new Date(dateStr);
      return `${d.toLocaleDateString()} ${d.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}`;
    }

    async function loadComments(ideaId, listEl) {
      const res = await fetch(`get-comments.php?idea_id=${ideaId}`);
      const comments = await res.json();
      listEl.innerHTML = '';
      comments.forEach(c => {
        const item = document.createElement('div');
        item.className = 'bg-gray-700 p-2 rounded';
        item.innerHTML = `<p class="text-sm text-gray-200">${c.text}</p><p class="text-xs text-gray-400 mt-1">${timeAgo(c.timestamp)}</p>`;
        listEl.appendChild(item);
      });
    }

    function bindVideoButtons() {
      const container = document.getElementById('video-container');

      container.querySelectorAll('.view-btn').forEach(btn => {
        btn.onclick = () => {
          const id = btn.dataset.id;
          fetch('increment-view.php', {
            method: 'POST',
            body: new URLSearchParams({ idea_id: id })
          });
          const n = +btn.textContent.split(' ')[1] + 1;
          btn.textContent = `👁 ${n}`;
        };
      });

      container.querySelectorAll('.like-btn').forEach(btn => {
        btn.onclick = () => {
          const id = btn.dataset.id;
          fetch('post-like.php', {
            method: 'POST',
            body: new URLSearchParams({ idea_id: id })
          }).then(() => {
            const n = +btn.textContent.split(' ')[1] + 1;
            btn.textContent = `👍 ${n}`;
          });
        };
      });

      container.querySelectorAll('.comment-toggle').forEach(btn => {
        const box = btn.closest('div').querySelector('.comment-box');
        btn.onclick = () => {
          box.classList.toggle('hidden');
          const id = btn.dataset.id;
          const list = box.querySelector('.comments-list');
          if (!box.classList.contains('hidden')) loadComments(id, list);
        };
      });

      container.querySelectorAll('.submit-comment').forEach(btn => {
        btn.onclick = () => {
          const id = btn.dataset.id;
          const input = btn.closest('.comment-box').querySelector('.comment-input');
          const list = btn.closest('.comment-box').querySelector('.comments-list');
          const txt = input.value.trim();
          if (!txt) return;

          fetch('post-comment.php', {
            method: 'POST',
            body: new URLSearchParams({ idea_id: id, comment: txt })
          }).then(() => {
            const toggle = btn.closest('.comment-box').previousElementSibling.querySelector('.comment-toggle');
            const n = +toggle.textContent.split(' ')[1] + 1;
            toggle.textContent = `💬 ${n}`;
            input.value = '';
            loadComments(id, list);
          });
        };
      });
    }

    async function checkSession() {
      const res = await fetch('check-session.php');
      const { loggedIn } = await res.json();
      const nav = document.getElementById('auth-section');
      const postButton = document.getElementById('post-button');

      if (loggedIn) {
        nav.innerHTML = `
          <div class="relative inline-block text-left">
            <button id="profileBtn" class="flex items-center space-x-2 bg-white text-indigo-700 font-semibold px-4 py-2 rounded-xl hover:bg-gray-200">
              Account
              <svg class="w-4 h-4 ml-1" viewBox="0 0 24 24">
                <path d="M19 9l-7 7-7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </button>
            <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-40 bg-white text-black rounded-lg shadow-lg">
              <a href="profile.html" class="block px-4 py-2 hover:bg-gray-100">Profile</a>
              <a href="logout.php" class="block px-4 py-2 hover:bg-gray-100">Log Out</a>
            </div>
          </div>`;
        postButton.innerHTML = `
          <a href="share.html" class="inline-block bg-purple-700 hover:bg-purple-900 text-white font-semibold px-8 py-3 rounded-xl">
            Post Your Idea
          </a>`;
        document.getElementById('profileBtn')
                .addEventListener('click', () => document.getElementById('dropdownMenu').classList.toggle('hidden'));
        document.addEventListener('click', e => {
          if (!document.getElementById('profileBtn').contains(e.target))
            document.getElementById('dropdownMenu').classList.add('hidden');
        });
      } else {
        nav.innerHTML = `
          <a href="signin.html" class="bg-white text-indigo-700 px-4 py-2 rounded-xl font-semibold">Sign In</a>
          <a href="signup.html" class="bg-white text-indigo-700 px-4 py-2 rounded-xl font-semibold">Sign Up</a>`;
      }
    }

    const params = new URLSearchParams(window.location.search);
    if (params.get('loggedout') === '1') {
      const msg = document.createElement('div');
      msg.textContent = 'You have been logged out.';
      msg.className = 'bg-green-700 text-white text-center py-3 mb-4 rounded shadow';
      document.body.prepend(msg);
      setTimeout(() => { msg.remove(); history.replaceState(null, '', window.location.pathname); }, 4000);
    }

    loadVideos();
    checkSession();
  </script>
</body>
</html>
