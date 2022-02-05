; Vernamova sifra na architekture DLX
; Tomas Cechvala xcechv03

        .data 0x04          ; zacatek data segmentu v pameti
login:  .asciiz "xcechv03"  ; <-- nahradte vasim loginem
cipher: .space 9 ; sem ukladejte sifrovane znaky (za posledni nezapomente dat 0)

        .align 2            ; dale zarovnavej na ctverice (2^2) bajtu
laddr:  .word login         ; 4B adresa vstupniho textu (pro vypis)
caddr:  .word cipher        ; 4B adresa sifrovaneho retezce (pro vypis)

        .text 0x40          ; adresa zacatku programu v pameti
        .global main        ; 

main:   ; sem doplnte reseni Vernamovy sifry dle specifikace v zadani
	
	loop:
	lb r29, login(r25)
	sgei r22, r29, 97
	bnez r22, znak
	nop
	beqz r22, false
	nop

	
	znak:
	snei r8,r8,0
	bnez r8, odcitat
	nop
	beqz r8, priratat
	nop

	priratat:
	addi r29, r29, 3
	sgei r13, r29, 123
	bnez r13, vacsie
	nop
	sb cipher(r25), r29
	addi r8,r8,1
	addi r25,r25,1
	j loop
	nop
	
	odcitat:
	subi r29, r29, 5
	slti r13, r29, 97
	bnez r13, mensie
	nop

	sb cipher(r25), r29
	
	sub r8,r8,r8
	addi r25,r25,1
	j loop
	nop

	vacsie:
	subi r29,r29,26
	sb cipher(r25), r29
	addi r8,r8,1
	addi r25,r25,1
	j loop
	nop
	
	mensie:
	addi r29,r29,26
	sb cipher(r25), r29
	sub r8,r8,r8
	addi r25,r25,1
	j loop
	nop

	false:
	addi r25,r25,1
	sb cipher(r25), r0
	
	
	


end:    addi r14, r0, caddr ; <-- pro vypis sifry nahradte laddr adresou caddr
        trap 5  ; vypis textoveho retezce (jeho adresa se ocekava v r14)
        trap 0  ; ukonceni simulace
