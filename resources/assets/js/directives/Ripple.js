// default max ripple size
const MAX_SIZE = 776

// offsetX polyfill
const getOffset = (event, touch) => {
  let target = event.currentTarget || event.target,
			rect = target.getBoundingClientRect()

	if (touch)
		return { x: event.touches[0].clientX - rect.left, y: event.touches[0].clientY - rect.top }
	else
		return { x: event.clientX - rect.left, y: event.clientY - rect.top }
}

const calcRadius = (c, maxSize) => {
  return Math.min(Math.max(c.offsetHeight, c.offsetWidth), maxSize)
}

export class Ripple {
  constructor(el, opts = {}) {
    // default settings
    this.container = el
    // prepare ripple container
    this.updateContainer()

		this.touchDevice = 'ontouchstart' in window;

		let clickEvent = this.touchDevice ? 'touchstart' : 'mousedown'

    el.addEventListener(clickEvent, this.spawnRipple.bind(this), { passive: true })
  }

  spawnRipple(event) {
    const container = this.container,
     			{ x, y } = getOffset(event, this.touchDevice),
     			radius = calcRadius(container, MAX_SIZE)

    // create the ripple & prepare it
    const ripple = document.createElement('div')
    ripple.className = 'ripple'

    // calc the ripple size
    Object.assign(ripple.style, {
      transform: 'scale(0) translate3d(0, 0, 0)',
      width: radius * 2 + 'px',
      height: radius * 2 + 'px',
      left: x - radius + 'px',
      top: y - radius + 'px',
      backgroundColor: this.backgroundColor
    })

    // Append child when the browser expects it
    window.requestAnimationFrame(() => {
      container.appendChild(ripple)

      // wait some time, so that the transition actually triggers
      window.requestAnimationFrame(() => {
        ripple.style.transform = `scale(1) translate3d(0, 0, 0)`
      })
    })

    // the next mouseup event should remove the ripple again
		let clickEvent = this.touchDevice ? 'touchend' : 'mouseup'

		window.addEventListener(clickEvent, this.removeRipple.bind(this, ripple), {
			passive: true,
			once: true,
		})

    return ripple
  }

  removeRipple(ripple) {
    if (!ripple) return

    // fade out ripple after some time
    setTimeout(() => ripple.style.opacity = 0, 150)

    // remove ripple after transition is done
    setTimeout(() => {
      window.requestAnimationFrame(() => {
        this.container.removeChild(ripple)
      })
    }, 300)
  }

  updateContainer() {
    this.container.classList.add('ripple-container')
    setTimeout(() => {
      this.backgroundColor = window.getComputedStyle(this.container).color
    }, 0)
  }

  destroy() {
    this.container.classList.remove('ripple-container')
    this.container.removeEventListener('mousedown', this.spawnRipple)
  }
}

export default {
  name: 'ripple',
  bind(el, binding, vnode) {
    el._ripple = new Ripple(el)
  },
  update(el) {
    el._ripple.updateContainer()
  },
  unbind(el) {
    el._ripple.destroy()
  },
}
