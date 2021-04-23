/*
 * Сохраняем ранее открытую вкладку на странице редактирования.
 * Принимает id tab, использовать стандартный tab bootstrap.
 */
export default function tabs_save(tabsId) {

    const tabsEdit = document.getElementById(tabsId),
        tabEditClass = 'nav-link',
        tabEditSave = localStorage.getItem(tabsId),
        tabEditSaveID = document.getElementById(tabEditSave)

    // Если сохранено в LocalStorage id элемента, то установим класс active к этому элементу
    if (tabsEdit && tabEditSave) {
        const tabEditLinks = tabsEdit.querySelectorAll('.' + tabEditClass),
            tabEditPanels = document.querySelectorAll('.tab-pane')

        if (tabEditSaveID && tabEditLinks) {
            tabEditLinks.forEach(function (el) {
                el.classList.remove('active')

                if (el.id === tabEditSave) {
                    el.classList.add('active')
                }

            })
        }

        if (tabEditSaveID && tabEditPanels) {
            tabEditPanels.forEach(function (el) {
                el.classList.remove('active')
                el.classList.remove('show')

                if (tabEditSave === el.getAttribute('aria-labelledby')) {
                    el.classList.add('show')
                    el.classList.add('active')
                }
            })
        }
    }

    // При клике на таб, запишем в LocalStorage id элемента
    if (tabsEdit) {
        tabsEdit.onclick = function(e) {
            if (e.target.classList.contains(tabEditClass)) {
                if (e.target.id) {
                    localStorage.setItem(tabsId, e.target.id)
                }
            }
        }
    }
}
