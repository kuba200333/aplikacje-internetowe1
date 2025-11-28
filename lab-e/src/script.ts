
const styles: Record<string, string> = {
    'Styl 1 (Jasny)': 'style-1.css',
    'Styl 2 (Ciemny)': 'style-2.css',
    'Styl 3 (Pomarańczowy)': 'style-3.css'
};

function loadStyle(styleName: string) {
    let link = document.getElementById('dynamic-style') as HTMLLinkElement;
    
    if (!link) {
        link = document.createElement('link');
        link.id = 'dynamic-style';
        link.rel = 'stylesheet';
        document.head.appendChild(link);
    }

    link.href = styles[styleName];
}

loadStyle('Styl 1 (Jasny)');

function generateLinks() {
    const container = document.getElementById('menu-list');
    if (!container) return;

    container.innerHTML = '';

    for (const key in styles) {
        const li = document.createElement('li');
        const a = document.createElement('a');
        
        a.textContent = key;
        a.href = '#';

        a.addEventListener('click', (e) => {
            e.preventDefault();
            loadStyle(key); 
        });
        
        li.appendChild(a);
        container.appendChild(li);
    }
}

generateLinks();

