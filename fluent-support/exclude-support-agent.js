document.addEventListener('DOMContentLoaded', () => {
  const wrapper = document.querySelector('.fs_all_tickets')
  if (!wrapper) return

  let lastTable = null

  const namesToHide = [
    'Md Kamrul Islam',
    'Shahjahan Jewel',
    'Masiur Siddiki'
  ]

  function hideRows (table) {
    table.querySelectorAll('tbody tr').forEach(row => {
      if (
        [...row.querySelectorAll('[title]')].some(el =>
          namesToHide.includes(el.getAttribute('title'))
        )
      ) {
        row.style.display = 'none'
      }
    })
  }

  function observeTableContent (table) {
    const tableObserver = new MutationObserver(() => {
      hideRows(table)
    })
    tableObserver.observe(table, { childList: true, subtree: true })
    hideRows(table)
  }

  function handleTableChange () {
    const table = wrapper.querySelector('.fs_all_tickets_table')
    if (table && table !== lastTable) {
      lastTable = table
      observeTableContent(table)
    }
  }

  const wrapperObserver = new MutationObserver(handleTableChange)
  wrapperObserver.observe(wrapper, { childList: true, subtree: true })

  handleTableChange()
})



