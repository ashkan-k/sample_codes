# Generated by Django 3.2 on 2021-04-19 19:20

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('Payment', '0002_alter_payment_amount'),
    ]

    operations = [
        migrations.AddField(
            model_name='payment',
            name='status',
            field=models.BooleanField(default=False, verbose_name='وضعیت'),
        ),
    ]
