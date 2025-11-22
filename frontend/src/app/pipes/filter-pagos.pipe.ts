import { Pipe, PipeTransform } from '@angular/core';
import { Pago } from '../../model/pagos.model';

@Pipe({
  name: 'filterPagos',
  standalone: false
})
export class FilterPagosPipe implements PipeTransform {

  transform(pagos: Pago[], texto: string): Pago[] {
    if (!pagos || !texto) return pagos;
    texto = texto.toLowerCase();
    return pagos.filter(p => 
      (p.numero_tarjeta?.toLowerCase().includes(texto) || '') ||
      (p.voucer?.toLowerCase().includes(texto) || '')
    );
  }

}