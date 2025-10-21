import './bootstrap'
import { createApp } from 'vue'

// ✅ Create the app instance
const app = createApp({})

// ✅ Expose it globally for Blade scripts
window.app = app

// ✅ Also make it available for ES modules (like <script type="module">)
export default app
