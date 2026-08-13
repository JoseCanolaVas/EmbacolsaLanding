export default function (to, from, savedPosition) {
    if (savedPosition) {
        return savedPosition
    }

    if (to.hash) {
        return new Promise((resolve) => {
            setTimeout(() => {
                resolve({
                    selector: to.hash,
                    offset: {
                        x: 0,
                        y: 88,
                    },
                })
            }, 150)
        })
    }

    return {
        x: 0,
        y: 0,
    }
}
