# Generated by Django 3.2 on 2021-04-23 12:00

from django.db import migrations


class Migration(migrations.Migration):

    dependencies = [
        ('User', '0003_resetpassword'),
    ]

    operations = [
        migrations.DeleteModel(
            name='ResetPassword',
        ),
    ]
