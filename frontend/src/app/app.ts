import { Component, signal } from '@angular/core';
import { Router } from '@angular/router';
import { PrimeNG } from 'primeng/config';
import { Subscription } from 'rxjs';

interface TabButton {
  label: string;
  icon?: string;
  route: string;
}

interface Tab {
  title: string;
  active?: boolean;
  buttons?: TabButton[];
}

interface AccordionGroup {
  title: string;
  active?: boolean;
  button?: ParentButton;
  tabs: Tab[];
}

interface ParentButton {
  label: string;
  icon?: string;
  route: string;
}

@Component({
  selector: 'app-root',
  templateUrl: './app.html',
  standalone: false,
  styleUrl: './app.css'
})
export class App {
  protected readonly title = signal('frontend');

  isAuthenticated: boolean = false;
  username: string | null = null;

  visibleMenuDrawer: boolean = true;
  visibleProfileDrawer: boolean = false;

  private authStatusSubscription: Subscription | null = null;

  activeRoute: string = '';
  currentLabel: string = '';

  menuItems = [
    { label: 'Panel General', icon: 'pi pi-th-large', route: '/panel' },
    { label: 'Reservas', icon: 'pi pi-calendar', route: '/reservas' },
    { label: 'Recursos', icon: 'pi pi-box', route: '/recursos' },
    { label: 'Proveedores', icon: 'pi pi-building', route: '/proveedores' },
    { label: 'Clientes', icon: 'pi pi-user', route: '/clientes' },
    { label: 'Pagos', icon: 'pi pi-credit-card', route: '/pagos' },
    { label: 'Notificaciones', icon: 'pi pi-bell', route: '/notificaciones' },
    { label: 'Configuración', icon: 'pi pi-cog', route: '/configuracion' },
  ];

  constructor(
    private router: Router,
    private primeng: PrimeNG,
  ) {}

  ngOnInit() {
    this.primeng.setTranslation({
      firstDayOfWeek: 1,
      dayNames: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
      dayNamesShort: ["D", "L", "M", "X", "J", "V", "S"],
      dayNamesMin: ["D", "L", "M", "X", "J", "V", "S"],
      monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
      monthNamesShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
      today: "Hoy",
      clear: "Limpiar"
    });

    this.router.events.subscribe(() => {
      this.activeRoute = this.router.url;
      const found = this.menuItems.find(item => item.route === this.activeRoute);
      this.currentLabel = found ? found.label : 'Panel General';
    });
  }

  ngOnDestroy() {
    if (this.authStatusSubscription) {
      this.authStatusSubscription.unsubscribe();
    }
  }

  cerrarSesion(): void {
    this.visibleProfileDrawer = false;
    localStorage.clear();
    sessionStorage.clear();
    this.router.navigate(['/logindashboard']);
  }

  toggleDrawer(): void {
    this.visibleMenuDrawer = !this.visibleMenuDrawer;
  }

  setActive(route: string) {
    this.activeRoute = route;
    const found = this.menuItems.find(item => item.route === route);
    this.currentLabel = found ? found.label : '';
    this.router.navigate([route]);
  }

  toggleGroup(group: AccordionGroup) {
    group.active = !group.active;
  }

  toggleTab(tab: Tab) {
    tab.active = !tab.active;
  }

  navigate(route: string) {
    this.router.navigate([route]);
  }
}