#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <sys/wait.h>
#include <sys/types.h>
#include <semaphore.h>
#include <fcntl.h>
#include <sys/shm.h>
#include <time.h>
#include <signal.h>
#include <stdbool.h>

FILE *f;
sem_t *queue, * all_finish, * print, * santa_queue, * santa_helped, * santa_hitched, *santa_finished_help, *santa_finished_hitch = NULL;
int* waiting, * A, * e_queue, * s_queue, * helped, * santa_help, *santa_hitch, *elf_holiday = NULL;
int waitingID, AID, e_queueID, s_queueID, helpedID, santa_helpID, santa_hitchID, elf_holidayID = 0;

int initSem() {

    //inicializacia zdielanych premennych medzi procesmi
    if (((waitingID = shmget(IPC_PRIVATE, sizeof(int), IPC_CREAT | 0666)) == -1) ||
        ((e_queueID = shmget(IPC_PRIVATE, sizeof(int), IPC_CREAT | 0666)) == -1) ||
		((s_queueID = shmget(IPC_PRIVATE, sizeof(int), IPC_CREAT | 0666)) == -1) ||
        ((helpedID = shmget(IPC_PRIVATE, sizeof(int), IPC_CREAT | 0666)) == -1) ||
        ((santa_helpID = shmget(IPC_PRIVATE, sizeof(int), IPC_CREAT | 0666)) == -1) ||
		((santa_hitchID = shmget(IPC_PRIVATE, sizeof(int), IPC_CREAT | 0666)) == -1) ||
		((AID = shmget(IPC_PRIVATE, sizeof(int), IPC_CREAT | 0666)) == -1) ||
		((elf_holidayID = shmget(IPC_PRIVATE, sizeof(int), IPC_CREAT | 0666)) == -1)) {
        return -1;
    }
    if (((waiting = shmat(waitingID, NULL, 0)) == NULL) ||
        ((e_queue = shmat(e_queueID, NULL, 0)) == NULL) ||
		((s_queue = shmat(s_queueID, NULL, 0)) == NULL) ||
        ((helped = shmat(helpedID, NULL, 0)) == NULL) ||
        ((santa_help = shmat(santa_helpID, NULL, 0)) == NULL) ||
		((santa_hitch = shmat(santa_hitchID, NULL, 0)) == NULL) ||
		((A = shmat(AID, NULL, 0)) == NULL) ||
		((elf_holiday = shmat(elf_holidayID, NULL, 0)) == NULL)) {
        return -1;
    }

    //inicializacia semaforov
    if (((queue = sem_open("/xcechv03_a_semaphore_queue", O_CREAT | O_EXCL, 0666, 1)) == SEM_FAILED) ||
		((print = sem_open("/xcechv03_a_semaphore_print", O_CREAT | O_EXCL, 0666, 1)) == SEM_FAILED) ||
		((santa_finished_help = sem_open("/xcechv03_a_semaphore_santa_finished_help", O_CREAT | O_EXCL, 0666, 0)) == SEM_FAILED) ||
		((santa_finished_hitch = sem_open("/xcechv03_a_semaphore_santa_finished_hitch", O_CREAT | O_EXCL, 0666, 0)) == SEM_FAILED) ||
        ((santa_queue = sem_open("/xcechv03_a_semaphore_santa_queue", O_CREAT | O_EXCL, 0666, 0)) == SEM_FAILED) ||
        ((santa_helped = sem_open("/xcechv03_a_semaphore_santa_helped", O_CREAT | O_EXCL, 0666, 0)) == SEM_FAILED) ||
        ((santa_hitched = sem_open("/xcechv03_a_semaphore_santa_hitched", O_CREAT | O_EXCL, 0666, 0)) == SEM_FAILED) ||
        ((all_finish = sem_open("/xcechv03_a_semaphore_all_finish", O_CREAT | O_EXCL, 0666, 0)) == SEM_FAILED)) {
        return -1;
    }
    return 0;
}

void cleanALL() {

    //uvolnenie zdielanych premennych
    if (waitingID != 0) shmctl(waitingID, IPC_RMID, NULL);
    if (e_queueID != 0) shmctl(e_queueID, IPC_RMID, NULL);
	if (s_queueID != 0) shmctl(s_queueID, IPC_RMID, NULL);
    if (helpedID != 0) shmctl(helpedID, IPC_RMID, NULL);
    if (AID != 0) shmctl(AID, IPC_RMID, NULL);
	if (elf_holidayID != 0) shmctl(elf_holidayID, IPC_RMID, NULL);
    if (santa_helpID != 0) shmctl(santa_helpID, IPC_RMID, NULL);
	if (santa_hitchID != 0) shmctl(santa_hitchID, IPC_RMID, NULL);

    //uvolnenie semaforov
    sem_unlink("/xcechv03_a_semaphore_queue");
    if (queue != NULL) sem_close(queue);
	sem_unlink("/xcechv03_a_semaphore_print");
    if (print != NULL) sem_close(print);
	sem_unlink("/xcechv03_a_semaphore_santa_finished_help");
    if (santa_finished_help != NULL) sem_close(santa_finished_help);
	sem_unlink("/xcechv03_a_semaphore_santa_finished_hitch");
    if (santa_finished_hitch != NULL) sem_close(santa_finished_hitch);
    sem_unlink("/xcechv03_a_semaphore_santa_queue");
    if (santa_queue != NULL) sem_close(santa_queue);
    sem_unlink("/xcechv03_a_semaphore_santa_helped");
    if (santa_helped != NULL) sem_close(santa_helped);
    sem_unlink("/xcechv03_a_semaphore_santa_hitched");
    if (santa_hitched != NULL) sem_close(santa_hitched);
    sem_unlink("/xcechv03_a_semaphore_all_finish");
    if (all_finish != NULL) sem_close(all_finish);
}

int elf(int I, int TE){

    //akcie vykonavane elfami
    srand(time(NULL));
	
	if (TE > 0){
		int elf_work = rand() % (TE);
		usleep(elf_work);
	}

    sem_wait(print);
    fprintf(f,"%d: Elf %d: need help\n",++(*A),I);
    sem_post(print);
	
	if (*santa_hitch == 1){
		sem_wait(santa_hitched);
		sem_post(santa_hitched);
        sem_wait(print);
        fprintf(f,"%d: Elf %d: taking holidays\n",++(*A),I);
		(*elf_holiday)++;
        sem_post(print);
		sem_post(santa_helped);

        //ukoncenie procesu
        return 1;
    }
	
	sem_wait(queue);
    (*e_queue)++;
    if (*e_queue >= 3 && *santa_hitch == 0){
		*santa_help = 1;
		*e_queue = *e_queue - 3;
		sem_post(santa_queue);
    }
	sem_post(queue);
	
    // cakanie na pomoc od santy
    sem_wait(santa_helped);
	if (*santa_help == 1 && *santa_hitch == 0){
		sem_wait(queue);
		(*helped)++;
		sem_post(queue);
		if (*helped < 3 ){
			sem_wait(print);
			fprintf(f,"%d: Elf %d: get help\n",++(*A),I);
			sem_post(print);
			sem_post(santa_helped);
			
			//pokracovanie v procese
			return 0;
		}
		else if (*helped == 3){
			sem_wait(print);
			fprintf(f,"%d: Elf %d: get help\n",++(*A),I);
			sem_post(print);
			*santa_help = 0;
			*helped = 0;
			sem_post(santa_finished_help);
			
			//pokracovanie v procese
			return 0;
		}
	}
	else if (*santa_hitch == 1){
		sem_wait(santa_hitched);
		sem_post(santa_hitched);
        sem_wait(print);
        fprintf(f,"%d: Elf %d: taking holidays\n",++(*A),I);
		(*elf_holiday)++;
        sem_post(print);
		sem_post(santa_helped);

        //ukoncenie procesu
        return 1;
    }
	return -1;
    
}

int santa(int sleep){
    
	if (!sleep){
	sem_wait(print);
    fprintf(f,"%d: Santa: going to sleep\n",++(*A));
    sem_post(print);
	}
    
	sem_wait(santa_queue);
	if (*santa_hitch == 0 && *santa_help == 0){
		sem_wait(santa_queue);
	}
	
	if (*santa_hitch == 1){
        sem_wait(print);
        fprintf(f,"%d: Santa: closing workshop\n",++(*A));
        sem_post(print);
		*s_queue = 0;
        sem_post(santa_hitched);
		sem_wait(santa_finished_hitch);
		
		// santa pusti vsetkych cakajucich elfov domov
		sem_post(santa_helped);
		
		// ukoncenie procesu santa
        return 1;
    }
	else if (*santa_help == 1){
        sem_wait(print);
        fprintf(f,"%d: Santa: helping elves\n",++(*A));
        sem_post(print);
        sem_post(santa_helped);
		
		// pokracovanie procesu santa
		sem_wait(santa_finished_help);
        return 0;
    }
	return 2;
}

int sob(int I,int TR,int NR){
    sem_wait(print);
    fprintf(f,"%d: RD %d: rstarted\n",++(*A),I);
    sem_post(print);

    if (TR > 0){
		int sob_holiday = rand() % (TR);
		usleep(sob_holiday);
	}

    sem_wait(print);
    fprintf(f,"%d: RD %d: return home\n",++(*A),I);
    sem_post(print);
    
	(*s_queue)++;
    if (*s_queue == NR){
		if (*santa_help == 1){
			sem_wait(santa_finished_help);
			sem_post(santa_finished_help);
		}
		*santa_hitch = 1;
		sem_post(santa_queue);
	}

    sem_wait(santa_hitched);
    sem_wait(print);
    fprintf(f,"%d: RD %d: get hitched\n",++(*A),I);
    sem_post(print);
    sem_post(santa_hitched);
	
	
	sem_wait(queue);
	(*s_queue)++;
	if (*s_queue == NR){
		sem_post(santa_finished_hitch);
	}
	sem_post(queue);
	
    (*waiting)--;
	if ((*waiting) == 0){ 
		//printf("finished all processes\n");
		sem_post(all_finish);
	};
	//printf("ended proces sob %d\n",I);
	exit(0);
}


int main(int argc, char* argv[]) {
	
	cleanALL();
	
    // kontrola argumentov
    if (argc != 5) {
        fprintf(stderr, "Invalid arguments\n");
        return -1;
    }
    char* s;
    int NE = strtol(argv[1], &s, 10); // počet elfu
    int NR = strtol(argv[2], &s, 10); // počet sobů
    int TE = strtol(argv[3], &s, 10); // maximální doba v milisekundách, po kterou skřítek pracuje samostatně
    int TR = strtol(argv[4], &s, 10); // maximální doba v milisekundách, po které se sob vrací z dovolené domů
    if (NE < 0 || NE > 1000 || NR < 0 || NR > 20 || TE < 0 || TE > 1000 || TR < 0 || TR > 1000) {
        fprintf(stderr, "Invalid arguments\n");
        return -1;
    }

    //Otvorenie suboru pre zapis
    f = fopen("proj2.out", "w");
    if (f == NULL) {
        fprintf(stderr, "ERROR - unable to open file");
        return -1;
    }
    setbuf(f, NULL);

    //inicializacia zdielanych premennych & semaforov
    if (initSem() == -1) {
        cleanALL();
        fprintf(stderr, "ERROR -semaphore/shared variable initialization failed\n");
    }

    //pociatocne hodnoty zdielanych premmenych
    *A = 0; //poradove cislo vykonavanej akcie
    *waiting = NE + NR + 4; //pocet procesov, ktore treba ukoncit pred hlavnym procesom(NE elfov + NR sobov + 2 pomocne procesy + 1 proces santa)
    *e_queue = 0; // pocet cakajucich elfu
    *santa_help = 0; // akcie vykonavane santom -> pomoc elfom
	*santa_hitch = 0; // akcie vykonavane santom -> zapriahnutie sobov
    *helped = 0; // pocet sobov, ktori dostali pomoc
	*elf_holiday = 0; // pocet elfov na dovolené
	
	
    pid_t pid1 = fork();
	
    // pomocny proces na tvorbu elfu
    if (pid1 == 0) {
        srand(time(NULL));
        for (int i = 1; i <= NE; i++) {

            // proces elf
            pid_t ELF = fork();
            if (ELF == 0) {
				// zaciatok procesu
				sem_wait(print);
				fprintf(f,"%d: Elf %d: started\n",++(*A),i);
				sem_post(print);
				
                int closed = 0;
                while (!closed){
                    closed = elf(i, TE);
                    if (closed){
                        (*waiting)--;
						if ((*waiting) == 0){ 
							//printf("finished all processes\n");
							sem_post(all_finish);
						};
						//printf("ended process elf %d\n",i);
						exit(0);
					}
                }
            }
        }

        //ukoncenie pomocneho procesu
        (*waiting)--;
		if ((*waiting) == 0){ 
			//printf("finished all processes\n");
			sem_post(all_finish);
		};
		//printf("ended help elf process\n");
		exit(0);
    }

    // ostatne procesy
    else if (pid1 > 0) {
        pid_t pid2 = fork();

        //proces santa
        if (pid2 == 0) {
            int closed = 0;
            while (!closed){
                closed = santa(0);
                if (closed == 1){
                    sem_wait(print);
                    fprintf(f,"%d: Santa: Christmas started\n",++(*A));
					sem_post(print);
					*santa_help = 0;
					sem_post(santa_helped);
                    (*waiting)--;
                    if ((*waiting) == 0){ 
					//printf("finished all processes\n");
					sem_post(all_finish);
					};
					//printf("ended process santa\n");
                    exit(0);
                }
				else if (closed == 2){
					closed = santa(1);
				}
				else if(closed == -1){
					fprintf(stderr,"Error internal\n");
				}
            }
        }

        // ostatne procesy
        else if (pid2 > 0){
            pid_t pid3 = fork();

            // pomocny proces pre tvorvbu sobov
            if (pid3 == 0){
                for (int j = 1; j <= NR; j++) {
                    pid_t SOB = fork();
                    if (SOB == 0){
                        sob(j,TR,NR);
                    }
                }
				//ukoncenie pomocneho procesu
				(*waiting)--;
				if ((*waiting) == 0){ 
					//printf("finished all processes\n");
					sem_post(all_finish);
				};
				//printf("ended help sob process\n");
				exit(0);
			}

            // pomocny proces pre ukoncenie
            else{
                pid_t pid4 = fork();
				if (pid4 == 0){
					sem_wait(santa_finished_hitch);
					sem_post(santa_finished_hitch);
					if (*s_queue == NR && *elf_holiday == NE){
						sem_post(all_finish);
					}
					//ukoncenie pomocneho procesu
					(*waiting)--;
					if ((*waiting) == 0){ 
						//printf("finished all processes\n");
						sem_post(all_finish);
					};
					//printf("ended help sob process\n");
					exit(0);
				}
				
				//hlavny proces, pokracuje az ked skoncia vsekty ostatne procesy
				else{
				sem_wait(all_finish);
				}
            }
        }
    }
    else {
        fprintf(stderr, "ERROR - proces creation failed\n");
    }
    cleanALL();
	fclose(f);
	return 0;

}
