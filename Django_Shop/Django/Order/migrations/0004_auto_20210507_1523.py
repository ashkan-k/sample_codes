# Generated by Django 3.2 on 2021-05-07 10:53

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('Order', '0003_alter_order_payment'),
    ]

    operations = [
        migrations.AlterField(
            model_name='order',
            name='address2',
            field=models.TextField(blank=True, null=True, verbose_name='آدرس 2'),
        ),
        migrations.AlterField(
            model_name='order',
            name='payment_type',
            field=models.CharField(blank=True, choices=[('online', 'آنلاین'), ('cash', 'نقدی')], max_length=6, null=True, verbose_name='نوع پرداخت'),
        ),
        migrations.AlterField(
            model_name='order',
            name='status',
            field=models.CharField(blank=True, choices=[('sending', 'درحال ارسال'), ('posted', 'ارسال شده'), ('delivered', 'تحویل داده شده')], default='sending', max_length=9, null=True, verbose_name='وضعیت ارسال'),
        ),
    ]