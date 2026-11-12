import './bootstrap';

import Alpine from 'alpinejs';
import { registerAlpineComponents } from './alpine/components';
import { registerDocumentViewer } from './alpine/documentViewer'; 

window.Alpine = Alpine;

registerAlpineComponents(Alpine);
registerDocumentViewer(Alpine); // Tambahkan ini

Alpine.start();
