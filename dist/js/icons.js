$(window).on("load", function () {
	$("span.custom-icon").each(function (ind, emt) {
		const name = $(emt).text().trim();
		switch (name) {
			case "down_pointer":
				$(emt).replaceWith(
					`<svg xmlns="http://www.w3.org/2000/svg" width="53" height="34" viewBox="0 0 53 34">
						<g id="Polygon_4" data-name="Polygon 4" transform="translate(53 34) rotate(180)" fill="#fef1e2">
							<path d="M 51.97636795043945 33.5 L 1.023633718490601 33.5 L 26.5 0.8133413195610046 L 51.97636795043945 33.5 Z" stroke="none"/>
							<path d="M 26.5 1.626693725585938 L 2.047275543212891 33 L 50.95272064208984 33 L 26.5 1.626693725585938 M 26.5 0 L 53 34 L 0 34 L 26.5 0 Z" stroke="none" fill="#f69d35"/>
						</g>
					</svg>
					`
				);
				$(emt).remove();
				break;
			case "up_pointer":
				$(emt).replaceWith(
					`<svg xmlns="http://www.w3.org/2000/svg" width="53" height="34" viewBox="0 0 53 34">
						<g id="Polygon_5" data-name="Polygon 5" fill="rgba(224,85,68,0.2)">
							<path d="M 51.97636795043945 33.5 L 1.023633718490601 33.5 L 26.5 0.8133413195610046 L 51.97636795043945 33.5 Z" stroke="none"/>
							<path d="M 26.5 1.626693725585938 L 2.047275543212891 33 L 50.95272064208984 33 L 26.5 1.626693725585938 M 26.5 0 L 53 34 L 0 34 L 26.5 0 Z" stroke="none" fill="#e05544"/>
						</g>
					</svg>					
					`
				);
				$(emt).remove();
				break;
			case "linkedin":
				$(emt).replaceWith(
					`<svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><title>Logo Linkedin</title><path d="M444.17 32H70.28C49.85 32 32 46.7 32 66.89v374.72C32 461.91 49.85 480 70.28 480h373.78c20.54 0 35.94-18.21 35.94-38.39V66.89C480.12 46.7 464.6 32 444.17 32zm-273.3 373.43h-64.18V205.88h64.18zM141 175.54h-.46c-20.54 0-33.84-15.29-33.84-34.43 0-19.49 13.65-34.42 34.65-34.42s33.85 14.82 34.31 34.42c-.01 19.14-13.31 34.43-34.66 34.43zm264.43 229.89h-64.18V296.32c0-26.14-9.34-44-32.56-44-17.74 0-28.24 12-32.91 23.69-1.75 4.2-2.22 9.92-2.22 15.76v113.66h-64.18V205.88h64.18v27.77c9.34-13.3 23.93-32.44 57.88-32.44 42.13 0 74 27.77 74 87.64z"/></svg>`
				);
				$(emt).remove();
				break;
			case "facebook":
				$(emt).replaceWith(
					`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg>`
				);
				$(emt).remove();
				break;
			case "twitter":
				$(emt).replaceWith(
					`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"/></svg>`
				);
				$(emt).remove();
				break;
			case "linkedin_out":
				$(emt).replaceWith(
					`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/></svg>`
				);
				$(emt).remove();
				break;
			case "calendar":
				$(emt).replaceWith(
					`<svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><title>Calendar</title><path d="M32 456a24 24 0 0024 24h400a24 24 0 0024-24V176H32zm320-244a4 4 0 014-4h40a4 4 0 014 4v40a4 4 0 01-4 4h-40a4 4 0 01-4-4zm0 80a4 4 0 014-4h40a4 4 0 014 4v40a4 4 0 01-4 4h-40a4 4 0 01-4-4zm-80-80a4 4 0 014-4h40a4 4 0 014 4v40a4 4 0 01-4 4h-40a4 4 0 01-4-4zm0 80a4 4 0 014-4h40a4 4 0 014 4v40a4 4 0 01-4 4h-40a4 4 0 01-4-4zm0 80a4 4 0 014-4h40a4 4 0 014 4v40a4 4 0 01-4 4h-40a4 4 0 01-4-4zm-80-80a4 4 0 014-4h40a4 4 0 014 4v40a4 4 0 01-4 4h-40a4 4 0 01-4-4zm0 80a4 4 0 014-4h40a4 4 0 014 4v40a4 4 0 01-4 4h-40a4 4 0 01-4-4zm-80-80a4 4 0 014-4h40a4 4 0 014 4v40a4 4 0 01-4 4h-40a4 4 0 01-4-4zm0 80a4 4 0 014-4h40a4 4 0 014 4v40a4 4 0 01-4 4h-40a4 4 0 01-4-4zM456 64h-55.92V32h-48v32H159.92V32h-48v32H56a23.8 23.8 0 00-24 23.77V144h448V87.77A23.8 23.8 0 00456 64z"/></svg>`
				);
				$(emt).remove();
				break;
			case "location":
				$(emt).replaceWith(
					`<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>`
				);
				$(emt).remove();
				break;
			case "shield":
				$(emt).replaceWith(
					`<svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><title>Shield Half</title><path d="M256 32C174 69.06 121.38 86.46 32 96c0 77.59 5.27 133.36 25.29 184.51a348.86 348.86 0 0071.43 112.41c49.6 52.66 104.17 80.4 127.28 87.08 23.11-6.68 77.68-34.42 127.28-87.08a348.86 348.86 0 0071.43-112.41C474.73 229.36 480 173.59 480 96c-89.38-9.54-142-26.94-224-64zm161.47 233.93a309.18 309.18 0 01-63.31 99.56C316 406 276.65 428.31 256 437.36V75.8c38.75 17 68.73 28.3 97.93 36.89a613.12 613.12 0 0085.6 18.52c-1.72 60.22-8.36 99.69-22.06 134.72z"/></svg>`
				);
				$(emt).remove();
				break;
			case "hourglass":
				$(emt).replaceWith(
					`<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M6 2v6h.01L6 8.01 10 12l-4 4 .01.01H6V22h12v-5.99h-.01L18 16l-4-4 4-3.99-.01-.01H18V2H6zm10 14.5V20H8v-3.5l4-4 4 4zm-4-5l-4-4V4h8v3.5l-4 4z"/></svg>`
				);
				$(emt).remove();
				break;
			case "target":
				$(emt).replaceWith(
					`<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-target" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="#727376" fill="none" stroke-linecap="round" stroke-linejoin="round">
						<path stroke="none" d="M0 0h24v24H0z" fill="none"/>
						<circle cx="12" cy="12" r="1" />
						<circle cx="12" cy="12" r="5" />
						<circle cx="12" cy="12" r="9" />
					</svg>`
				);
				$(emt).remove();
				break;
			case "users":
				$(emt).replaceWith(
					`<svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><title>People</title><circle cx="152" cy="184" r="72"/><path d="M234 296c-28.16-14.3-59.24-20-82-20-44.58 0-136 27.34-136 82v42h150v-16.07c0-19 8-38.05 22-53.93 11.17-12.68 26.81-24.45 46-34z"/><path d="M340 288c-52.07 0-156 32.16-156 96v48h312v-48c0-63.84-103.93-96-156-96z"/><circle cx="340" cy="168" r="88"/></svg>`
				);
				$(emt).remove();
				break;
			case "upvote":
				$(emt).replaceWith(
					`<svg xmlns="http://www.w3.org/2000/svg" width="36" height="21" viewBox="0 0 36 21">
						<path id="Polygon_11" data-name="Polygon 11" d="M15.722,2.657a3,3,0,0,1,4.556,0l11.477,13.39A3,3,0,0,1,29.477,21H6.523a3,3,0,0,1-2.278-4.952Z"/>
					</svg>					
					`
				);
				$(emt).remove();
				break;
			case "downvote":
				$(emt).replaceWith(
					`<svg xmlns="http://www.w3.org/2000/svg" width="36" height="21" viewBox="0 0 36 21">
						<path id="Polygon_12" data-name="Polygon 12" d="M15.722,2.657a3,3,0,0,1,4.556,0l11.477,13.39A3,3,0,0,1,29.477,21H6.523a3,3,0,0,1-2.278-4.952Z" transform="translate(36 21) rotate(180)"/>
					</svg>		
					`
				);
				$(emt).remove();
				break;
			case "like":
				$(emt).replaceWith(
					`<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M16.5 3c-1.74 0-3.41.81-4.5 2.09C10.91 3.81 9.24 3 7.5 3 4.42 3 2 5.42 2 8.5c0 3.78 3.4 6.86 8.55 11.54L12 21.35l1.45-1.32C18.6 15.36 22 12.28 22 8.5 22 5.42 19.58 3 16.5 3zm-4.4 15.55l-.1.1-.1-.1C7.14 14.24 4 11.39 4 8.5 4 6.5 5.5 5 7.5 5c1.54 0 3.04.99 3.57 2.36h1.87C13.46 5.99 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5 0 2.89-3.14 5.74-7.9 10.05z"/></svg>
					`
				);
				$(emt).remove();
				break;
		}
	});
});
