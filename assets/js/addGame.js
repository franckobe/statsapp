document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('formAddGameFile').addEventListener('submit', async (e) => {
    e.preventDefault()
    const form = e.currentTarget
    try {
      const response = await fetch(form.action, {
        method: form.method,
        body: new FormData(form)
      })
      const json = await response.json()
      alert(json.message)
      if (json.status) {
        form.reset()
      }
    } catch (e) {
      console.error(e)
    }
  })
})
