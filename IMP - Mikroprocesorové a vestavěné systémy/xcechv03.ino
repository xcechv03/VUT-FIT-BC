#ifndef LED_BUILTIN
#define LED_BUILTIN 13
#endif

int counter = 0;          //pocitadlo zabliknuti ledky v normalnom stave
int touch_sensor_value = 0;   //hodnota touch senzoru 

TaskHandle_t task1_handle = NULL;       //zastavovanie/obnovenie tasku

// 2 tasky - touch a blikanie

void TaskBlikaj( void *parameter ){

    (void) parameter;
   
    
    for (;;){
    digitalWrite(LED_BUILTIN, HIGH);   // zapni LED
    vTaskDelay(3000 / portTICK_PERIOD_MS);  // tick delay 3sekundy
    digitalWrite(LED_BUILTIN, LOW);    // vypni LED
    vTaskDelay(2500 / portTICK_PERIOD_MS);  // tick delay 2,5 sekundy
    counter++;
    Serial.print("Pocet zablikani ledky bez stlacenia senzoru = ");
    Serial.println(counter);
  }
    
}

void TaskTouch( void *parameter ){

    (void) parameter;
    
    
    for (;;){
    touch_sensor_value = touchRead(T0);               //zisti hodnotu na senzore T0
    Serial.print("Hodnota dotyku senzoru T0 = ");
    Serial.println(touch_sensor_value);
    if(touch_sensor_value < 50){                      //ak je touch senzor dotknuty blikni 2krat
     digitalWrite(LED_BUILTIN, HIGH);
      vTaskDelay(100 / portTICK_PERIOD_MS);
      digitalWrite(LED_BUILTIN, LOW);
      vTaskDelay(100 / portTICK_PERIOD_MS);
       digitalWrite(LED_BUILTIN, HIGH);
      vTaskDelay(100 / portTICK_PERIOD_MS);
      digitalWrite(LED_BUILTIN, LOW);
      }
    vTaskDelay(1000 / portTICK_PERIOD_MS);
  }

}

// tasky


void setup() {

  Serial.begin(115200);
  pinMode(LED_BUILTIN, OUTPUT);
 
    xTaskCreate(
    TaskBlikaj,         //nazov funkcie
    "Task Blikaj",      //nazov tasku
    1024,               //stack size
    NULL,               //parametre funkcie
    1,                  //priorita
    &task1_handle       //handle
    );
     
    xTaskCreate(
    TaskTouch,
    "Task Touch",
    1024,
    NULL,
    1,
    NULL
    );

}
void loop() {
  if(touch_sensor_value < 50 && task1_handle != NULL){          //implementacia zastavenia tasku blikaj ak je stlaceny touch senzor
      vTaskSuspend(task1_handle);
      vTaskDelay(100 / portTICK_PERIOD_MS);
      
      }
  if(touch_sensor_value >= 50 && task1_handle != NULL){         //obnovenie tasku blikaj ak uz nie je stlaceny touch senzor
      vTaskResume(task1_handle);
      vTaskDelay(100 / portTICK_PERIOD_MS);
  }
}
