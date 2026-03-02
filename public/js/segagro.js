// public/js/segagro.js

let currentDate = new Date();
const months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
               'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggle = document.querySelector('.password-toggle i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggle.className = 'fas fa-eye-slash';
    } else {
        passwordInput.type = 'password';
        toggle.className = 'fas fa-eye';
    }
}

function setActive(element) {
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });
    element.classList.add('active');
}

function previousMonth() {
    currentDate.setMonth(currentDate.getMonth() - 1);
    updateCalendar();
    generateCalendar();
}

function nextMonth() {
    currentDate.setMonth(currentDate.getMonth() + 1);
    updateCalendar();
    generateCalendar();
}

function updateCalendar() {
    const monthElement = document.getElementById('currentMonth');
    if (monthElement) {
        monthElement.textContent = 
            months[currentDate.getMonth()] + ' ' + currentDate.getFullYear();
    }
}

function generateCalendar() {
    const calendarGrid = document.getElementById('calendarGrid');
    if (!calendarGrid) return;

    // Limpiar calendario
    calendarGrid.innerHTML = '';

    // Headers de días
    const dayHeaders = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
    dayHeaders.forEach(day => {
        const headerDiv = document.createElement('div');
        headerDiv.className = 'calendar-day header';
        headerDiv.textContent = day;
        calendarGrid.appendChild(headerDiv);
    });

    // Obtener primer día del mes y días en el mes
    const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
    const startDate = firstDay.getDay();
    const daysInMonth = lastDay.getDate();

    // Días vacíos al inicio
    for (let i = 0; i < startDate; i++) {
        const emptyDiv = document.createElement('div');
        emptyDiv.className = 'calendar-day';
        calendarGrid.appendChild(emptyDiv);
    }

    // Días del mes
    const today = new Date();
    const events = getCalendarEvents(); // Función para obtener eventos

    for (let day = 1; day <= daysInMonth; day++) {
        const dayDiv = document.createElement('div');
        dayDiv.className = 'calendar-day';
        dayDiv.textContent = day;

        // Marcar día actual
        if (currentDate.getFullYear() === today.getFullYear() &&
            currentDate.getMonth() === today.getMonth() &&
            day === today.getDate()) {
            dayDiv.classList.add('current');
        }

        // Agregar eventos si existen
        const dayEvents = events[day];
        if (dayEvents && dayEvents.length > 0) {
            dayDiv.classList.add('has-event');
            dayEvents.forEach(event => {
                const eventDiv = document.createElement('div');
                eventDiv.className = 'event';
                eventDiv.textContent = event;
                dayDiv.appendChild(eventDiv);
            });
        }

        calendarGrid.appendChild(dayDiv);
    }
}

function getCalendarEvents() {
    // Eventos de ejemplo - reemplazar con datos reales
    return {
        9: ['Revision saber Científico'],
        11: ['Revision saber Científico'],
        15: ['Semestre Sergio Ramos'],
        16: ['Contrato'],
        23: ['Revision saber Científico']
    };
}

function initializeCharts() {
    // Simular gráfico de presupuesto
    const accountingCanvas = document.getElementById('accountingChart');
    if (accountingCanvas) {
        const accountingCtx = accountingCanvas.getContext('2d');
        
        // Limpiar canvas
        accountingCtx.clearRect(0, 0, accountingCanvas.width, accountingCanvas.height);
        
        // Dibujar líneas de ejemplo
        drawLineChart(accountingCtx, accountingCanvas.width, accountingCanvas.height, 
                     [150, 200, 180, 300, 250, 280, 200], '#e74c3c', 'Egresos');
        drawLineChart(accountingCtx, accountingCanvas.width, accountingCanvas.height, 
                     [100, 180, 220, 200, 320, 300, 250], '#4cd137', 'Ingresos');
    }

    // Simular gráfico de balance
    const balanceCanvas = document.getElementById('balanceChart');
    if (balanceCanvas) {
        const balanceCtx = balanceCanvas.getContext('2d');
        
        balanceCtx.clearRect(0, 0, balanceCanvas.width, balanceCanvas.height);
        drawBarChart(balanceCtx, balanceCanvas.width, balanceCanvas.height);
    }
}

function drawLineChart(ctx, width, height, data, color, label) {
    const padding = 40;
    const chartWidth = width - 2 * padding;
    const chartHeight = height - 2 * padding;
    
    ctx.strokeStyle = color;
    ctx.lineWidth = 3;
    ctx.beginPath();
    
    data.forEach((value, index) => {
        const x = padding + (index * chartWidth) / (data.length - 1);
        const y = padding + chartHeight - (value / 400) * chartHeight;
        
        if (index === 0) {
            ctx.moveTo(x, y);
        } else {
            ctx.lineTo(x, y);
        }
    });
    
    ctx.stroke();
    
    // Puntos
    ctx.fillStyle = color;
    data.forEach((value, index) => {
        const x = padding + (index * chartWidth) / (data.length - 1);
        const y = padding + chartHeight - (value / 400) * chartHeight;
        
        ctx.beginPath();
        ctx.arc(x, y, 4, 0, 2 * Math.PI);
        ctx.fill();
    });
}

function drawBarChart(ctx, width, height) {
    const padding = 40;
    const chartWidth = width - 2 * padding;
    const chartHeight = height - 2 * padding;
    const days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
    const egresosData = [120, 150, 80, 200, 180, 160, 140];
    const ingresosData = [100, 180, 120, 250, 200, 180, 160];
    
    const barWidth = chartWidth / (days.length * 2.5);
    
    days.forEach((day, index) => {
        const x = padding + index * (chartWidth / days.length);
        
        // Egresos (rojo)
        const egresosHeight = (egresosData[index] / 300) * chartHeight;
        ctx.fillStyle = '#e74c3c';
        ctx.fillRect(x, padding + chartHeight - egresosHeight, barWidth, egresosHeight);
        
        // Ingresos (verde)
        const ingresosHeight = (ingresosData[index] / 300) * chartHeight;
        ctx.fillStyle = '#4cd137';
        ctx.fillRect(x + barWidth + 5, padding + chartHeight - ingresosHeight, barWidth, ingresosHeight);
    });
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    updateCalendar();
    generateCalendar();
});

// Export functions for global access
window.togglePassword = togglePassword;
window.setActive = setActive;
window.previousMonth = previousMonth;
window.nextMonth = nextMonth;
window.initializeCharts = initializeCharts;