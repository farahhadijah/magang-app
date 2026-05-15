import './bootstrap';

import Alpine from 'alpinejs';
import { registerAlpineComponents } from './alpine/components';

window.Alpine = Alpine;

registerAlpineComponents(Alpine);

Alpine.start();
